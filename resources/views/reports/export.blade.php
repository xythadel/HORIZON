<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst(str_replace('-', ' ', $title)) }} Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px;}
        h2 { text-align: center;}
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>HORIZON</h2>
    <h2>{{ ucfirst(str_replace('-', ' ', $title)) }} Report</h2>

    @if(is_array($report) || $report instanceof \Illuminate\Support\Collection)
        @foreach($report as $entry)
            @if(isset($entry['user']))
                <!-- For Course Completion and Assessment -->
                <h4>User: {{ $entry['user'] }}</h4>
                @if(isset($entry['progress']))
                    <table>
                        <thead><tr><th>Course</th><th>Topics Complete</th></tr></thead>
                        <tbody>
                            @foreach($entry['progress'] as $p)
                                <tr><td>{{ $p['course'] }}</td><td>{{ $p['progress'] ?? 'N/A' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif(isset($entry['topics']))
                    <table>
                        <thead><tr><th>Title</th><th>Pre Avg</th><th>Post Avg</th><th>Gain</th></tr></thead>
                        <tbody>
                            @foreach($entry['topics'] as $topic)
                                <tr>
                                    <td>{{ $topic['title'] }}</td>
                                    <td>{{ $topic['pre_avg'] }}</td>
                                    <td>{{ $topic['post_avg'] }}</td>
                                    <td>{{ $topic['gain'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            @elseif(isset($entry['framework']))
                <!-- For Framework Comparison -->
                <table>
                    <thead>
                        <tr>
                            @foreach(array_keys($entry) as $header)
                                <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($entry as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>

            @elseif(isset($entry['name']) && isset($entry['email']) && isset($entry['badges_earned_count']))
                <!-- For Gamification -->
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Badges Earned</th>
                            <th>Total Attempt</th>
                            <th>Average Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $entry['name'] }}</td>
                            <td>{{ $entry['badges_earned_count'] }}</td>
                            <td>{{ $entry['total_quizzes_taken'] }}</td>
                            <td>{{ $entry['average_score'] }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @endforeach
    @else
        <p>No data available for this report.</p>
    @endif
</body>
</html>
