@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#">{{ $thread->creator->name }}</a> posted:
                        {{ $thread->title }}
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach ($thread->replies as $reply)
                    @include ('threads.reply')
                @endforeach

                @if (auth()->check())

                      <form action="{{ $thread->path() . '/replies' }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                          <label for="body">Body:</label>
                          <textarea class="form-control" id="body" name="body" rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-default">Post</button>
                      </form>
                @else
                  <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                @endif
            </div>

            <div class="col-md-4">
              <div class="panel panel-default">
                  <div class="panel-body">
                    This thread was published {{ $thread->created_at->diffForHumans() }} by
                    <a href="#">{{ $thread->creator->name }}</a>, and currently
                    has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                  </div>
              </div>
            </div>
        </div>
    </div>
@endsection
