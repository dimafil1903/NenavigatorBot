@extends('app')

@section('content')
    <h1>Messages Graphs</h1>
    <div class="container">
        @if($topChats)

            <ul class="list-group">
                @foreach($topChats as $chats)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{$chats['title']}}
                        <span class="badge badge-primary badge-pill"> {{$chats['count']}}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div style="width: 100%">
        {!! $usersChart->container() !!}
    </div>


    <div class="container">
        @if($triggers and $triggersCount)
            @foreach ($triggers as $trigger)

                <div class="row">
                    <div class="col-4 mt-2">
                        {{$trigger->trigger_message}}
                    </div>
                    <div class="col-5 mt-2">
                        {{$trigger->answer_message}}
                    </div>
                    <div class="col-3 mt-2">
                        <form method="post" action="{{url("/deltrigger")}}">
                            @csrf
                            <input name="triggerId" type="hidden" value={{$trigger->id}}>
                            <input type="submit" class="btn-danger" value="Delete">
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8">
    </script>
    @if($usersChart)
        {!! $usersChart->script() !!}
    @endif
@endsection
