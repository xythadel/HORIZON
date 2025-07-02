<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Badge, Course, QuizAttempt};
use Barryvdh\DomPDF\Facade\Pdf;

class BadgeController extends Controller
{
    public function index()
    {
        return Badge::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string'
        ]);

        return Badge::create($request->all());
    }
    
    public function update(Request $request, $id)
    {
        $badge = Badge::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|string',
        ]);

        $badge->update($validated);

        return response()->json(['message' => 'Badge updated successfully.', 'badge' => $badge]);
    }
    public function check($id)
    {
        $user = User::with('badges')->findOrFail($id);
        $earned = $user->badges->pluck('id')->toArray();

        $badges = [];

        // Check if user passed all Laravel topics
        $laravelCourse = Course::with('topics')->where('name', 'Laravel')->first();
        if ($laravelCourse) {
            $topicIds = $laravelCourse->topics->pluck('id');
            $passedTopics = QuizAttempt::where('user_id', $id)
                ->where('passed', true)
                ->whereIn('topic_id', $topicIds)
                ->pluck('topic_id')
                ->unique();

            if ($topicIds->diff($passedTopics)->isEmpty()) {
                $badge = Badge::where('title', 'Laravel Crusher')->first();
                if ($badge && !in_array($badge->id, $earned)) {
                    $badges[] = [
                        'id' => $badge->id,
                        'title' => $badge->title,
                        'description' => $badge->description,
                        'image' => $badge->image,
                        'claimable' => true
                    ];
                }
            }
        }

        return response()->json($badges);
    }

    public function claim(Request $request, $id)
    {
        $badge = Badge::findOrFail($request->badge_id);
        $user = User::findOrFail($id);

        if (!$user->badges->contains($badge->id)) {
            $user->badges()->attach($badge->id, ['claimed_at' => now()]);
            return response()->json(['message' => 'Badge claimed successfully.']);
        }

        return response()->json(['message' => 'Badge already claimed.'], 409);
    }

    public function claimed($id)
    {
        $user = User::with('badges')->findOrFail($id);
        return response()->json($user->badges);
    }

    public function generateCourseCompletionReport()
    {
        $users = User::where('role', '!=', 'admin')->with(['quizAttempts', 'quizAttempts.topic.course'])->get();

        $report = $users->map(function ($user) {
            $courses = $user->quizAttempts->groupBy(function ($attempt) {
                return optional($attempt->topic->course)->name;
            });

            $progress = [];

            foreach ($courses as $courseName => $attempts) {
                $totalTopics = $attempts->pluck('topic_id')->unique()->count();
                $passedTopics = $attempts->where('passed', true)->pluck('topic_id')->unique()->count();
                $progress[] = [
                    'course' => $courseName,
                    'progress' => $totalTopics
                ];
            }

            return [
                'user' => $user->name,
                'progress' => $progress
            ];
        });

        return response()->json($report);
    }

    public function generateFrameworkProficiencyScorecard()
    {
        $frameworks = ['Laravel', 'Vue'];

        $results = collect($frameworks)->map(function ($framework) {
            $course = Course::where('name', $framework)->with('topics')->first();
            if (!$course) return ['framework' => $framework, 'average_score' => 0];

            $topicIds = $course->topics->pluck('id');

            $attempts = QuizAttempt::whereIn('topic_id', $topicIds)
                ->orderByDesc('attempted_at')
                ->get();

            $groupedByUser = $attempts->groupBy('user_id');

            $totalUsers = $groupedByUser->count();
            $totalScore = 0;
            $totalPassed = 0;

            foreach ($groupedByUser as $userAttempts) {
                $latest = $userAttempts->first();
                $totalScore += $latest->score;
                if ($latest->passed) $totalPassed++;
            }

            $averageScore = $totalUsers > 0 ? $totalScore / $totalUsers : 0;

            return [
                'framework' => $framework,
                'Unique_Learners' => $totalUsers,
                'Passed_Learners' => $totalPassed,
                'Total_Score' => $totalScore,
                'Average_Score' => round($averageScore, 2)
            ];
        });

        return response()->json($results);
    }


    public function generateGamificationReport()
    {
        $users = User::where('role', '!=', 'admin')->with('badges', 'quizAttempts')->get();

        $leaderboard = $users->map(function ($user) {
            $totalQuizzes = $user->quizAttempts->count();
            $totalScore = $user->quizAttempts->sum('score');
            $averageScore = $totalQuizzes > 0 ? round($totalScore / $totalQuizzes, 2) : 0;

            return [
                'name' => $user->name,
                'email' => $user->email,
                'badges_earned_count' => $user->badges->count(),
                'badges_earned_titles' => $user->badges->pluck('title')->toArray(),
                'total_quizzes_taken' => $totalQuizzes,
                'average_score' => $averageScore,
            ];
        })->sortByDesc('badges_earned_count')->values();

        return response()->json($leaderboard);
    }



    public function generateAssessmentReport()
    {
        $users = User::where('role', '!=', 'admin')->with(['quizAttempts.topic'])->get();

        $report = $users->map(function ($user) {
            $topics = $user->quizAttempts->groupBy('topic_id');

            $topicReports = $topics->map(function ($attempts, $topicId) {
                $pre = $attempts->where('type', 'pre')->avg('score') ?? 0;
                $post = $attempts->where('type', 'post')->avg('score') ?? 0;
                $topicTitle = optional($attempts->first()->topic)->title ?? 'N/A';
                return [
                    'topic_id' => $topicId,
                    'title' => $topicTitle,
                    'pre_avg' => round($pre, 2),
                    'post_avg' => round($post, 2),
                    'gain' => round($post - $pre, 2)
                ];
            })->values();

            return [
                'user' => $user->name,
                'topics' => $topicReports
            ];
        });

        return response()->json($report);
    }


    public function generateFrameworkComparisonReport()
    {
        $frameworks = ['Laravel', 'Vue'];

        $report = collect($frameworks)->map(function ($framework) {
            $course = Course::where('name', $framework)->first();
            if (!$course) return null;

            $topicIds = $course->topics->pluck('id');
            $attempts = QuizAttempt::whereIn('topic_id', $topicIds)->get();

            return [
                'framework' => $framework,
                'users_attempted' => $attempts->pluck('user_id')->unique()->count(),
                'avg_score' => round($attempts->avg('score'), 2),
                'pass_rate' => round(($attempts->where('passed', true)->count() / max($attempts->count(), 1)) * 100, 2)
            ];
        })->filter();

        return response()->json($report);
    }

    public function exportReportToPdf(Request $request)
    {
        $type = $request->query('type');
        $data = [];

        switch ($type) {
            case 'course-completion':
                $data = json_decode(json_encode($this->generateCourseCompletionReport()->getData()), true);
                break;
            case 'framework-scorecard':
                $data = json_decode(json_encode($this->generateFrameworkProficiencyScorecard()->getData()), true);
                break;
            case 'gamification':
                $data = json_decode(json_encode($this->generateGamificationReport()->getData()), true);
                break;
            case 'assessment':
                $data = json_decode(json_encode($this->generateAssessmentReport()->getData()), true);
                break;
            case 'framework-comparison':
                $data = json_decode(json_encode($this->generateFrameworkComparisonReport()->getData()), true);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }


        $pdf = PDF::loadView('reports.export', ['report' => $data, 'title' => $type]);
        return $pdf->download("{$type}_report.pdf");
    }

    public function getBadges($id)
    {
        $user = User::with('quizAttempts')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $badges = Badge::all();
        $laravelTopics = \App\Models\Topic::whereHas('course', fn($q) => $q->where('name', 'Laravel'))->pluck('id');
        $vueTopics = \App\Models\Topic::whereHas('course', fn($q) => $q->where('name', 'Vue'))->pluck('id');
        $passedTopics = $user->quizAttempts
            ->where('type', 'post')
            ->where('passed', true)
            ->pluck('topic_id')
            ->unique();
        $totalScore = $user->quizAttempts->sum('score');
        $progressData = [];

        foreach ($badges as $badge) {
            $title = strtolower($badge->title);
            $progress = 0;
            $claimable = false;

            if (str_contains($title, 'laravel crusher')) {
                $completed = $passedTopics->intersect($laravelTopics)->count();
                $total = $laravelTopics->count();
                $progress = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
                $claimable = $progress == 100;
            }

            if (str_contains($title, 'vue veteran')) {
                $completed = $passedTopics->intersect($vueTopics)->count();
                $total = $vueTopics->count();
                $progress = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
                $claimable = $progress == 100;
            }

            if (str_contains($title, 'quiz master')) {
                $progress = min(round(($totalScore / 20) * 100, 2), 100);
                $claimable = $totalScore >= 20;
            }

            $progressData[$badge->id] = [
                'progress' => $progress,
                'claimable' => $claimable,
            ];
        }

        return response()->json([
            'user_id' => $user->id,
            'badges' => $badges,
            'progressData' => $progressData,
        ]);
    }


}
