@extends('layouts.admin')
@section('content')
  <div class="content-main-block mrg-t-40">
    <div class="admin-create-btn-block">
      <a href="{{route('movies.create')}}" class="btn btn-danger btn-md"><i class="material-icons left">add</i> Create Movie</a>
      <!-- Delete Modal -->
      <a type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#bulk_delete"><i class="material-icons left">delete</i> Delete Selected</a>   
      @if (Session::has('changed_language'))
        <a href="{{ route('tmdb_movie_translate') }}" class="btn btn-danger btn-md"><i class="material-icons left">translate</i> Translate all to {{Session::get('changed_language')}}</a>   
      @endif
      <!-- Modal -->
      <div id="bulk_delete" class="delete-modal modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <div class="delete-icon"></div>
            </div>
            <div class="modal-body text-center">
              <h4 class="modal-heading">Are You Sure ?</h4>
              <p>Do you really want to delete these records? This process cannot be undone.</p>
            </div>
            <div class="modal-footer">
              {!! Form::open(['method' => 'POST', 'action' => 'MovieController@bulk_delete', 'id' => 'bulk_delete_form']) !!}
                <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Yes</button>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="content-block box-body">
      <table id="movies_table" class="table table-hover">
        <thead>
          <tr class="table-heading-row">
            <th>
              <div class="inline">
                <input id="checkboxAll" type="checkbox" class="filled-in" name="checked[]" value="all" id="checkboxAll">
                <label for="checkboxAll" class="material-checkbox"></label>
              </div>
              #
            </th>
            <th>Thumbnail</th>
            <th>Movie Title</th>
            <th>Rating</th>
            <th>By TMDB</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        @if ($movies)
          <tbody>
            @php ($no = 1)
            @foreach ($movies as $movie)
              <tr>
                <td>
                  <div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="{{$movie->id}}" id="checkbox{{$movie->id}}">
                    <label for="checkbox{{$movie->id}}" class="material-checkbox"></label>
                  </div>
                  {{$no}}
                  @php ($no++)
                </td>
                <td><img
                  @if ($movie->thumbnail)
                    src="{{asset('/images/movies/thumbnails/' . $movie->thumbnail)}}"
                  @elseif($movie->poster)
                    src="{{asset('/images/movies/posters/' . $movie->poster)}}"
                  @else
                    src="http://via.placeholder.com/70x70"
                  @endif
                    alt="Pic" width="70px" class="img-responsive">
                </td>
                <td>{{$movie->title}}</td>
                <td>
                    IMDB {{$movie->rating}}
                </td>
                <td>
                  @if ($movie->tmdb == 'Y')
                    <i class="material-icons done">done</i>
                  @else
                    -
                  @endif
                </td>
                <td>{!!$movie->featured == 1 ? '<i class="material-icons done">done</i>' : '-'!!}</td>
                <td>
                  <div class="admin-table-action-block"> 
                    <a href="{{url('movie/detail', $movie->id)}}" data-toggle="tooltip" data-original-title="Page Preview" target="_blank" class="btn-default btn-floating"><i class="material-icons">desktop_mac</i></a>
                    <a href="{{route('movies.edit', $movie->id)}}" data-toggle="tooltip" data-original-title="Edit" class="btn-info btn-floating"><i class="material-icons">mode_edit</i></a>
                    <!-- Delete Modal -->
                    <button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#{{$movie->id}}deleteModal"><i class="material-icons">delete</i> </button>
                  </div>
                </td>
              </tr>
              <!-- Modal -->
                <div id="{{$movie->id}}deleteModal" class="delete-modal modal fade" role="dialog">
                  <div class="modal-dialog modal-sm">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="delete-icon"></div>
                      </div>
                      <div class="modal-body text-center">
                        <h4 class="modal-heading">Are You Sure ?</h4>
                        <p>Do you really want to delete these records? This process cannot be undone.</p>
                      </div>
                      <div class="modal-footer">
                        {!! Form::open(['method' => 'DELETE', 'action' => ['MovieController@destroy', $movie->id]]) !!}
                          <button type="reset" class="btn btn-gray translate-y-3" data-dismiss="modal">No</button>
                          <button type="submit" class="btn btn-danger">Yes</button>
                        {!! Form::close() !!}
                      </div>
                    </div>
                  </div>
                </div>
            @endforeach
          </tbody>
        @endif
      </table>
    </div>
  </div>
@endsection
@section('custom-script')
  <script>
    $(function(){
      $('#checkboxAll').on('change', function(){
        if($(this).prop("checked") == true){
          $('.material-checkbox-input').attr('checked', true);
        }
        else if($(this).prop("checked") == false){
          $('.material-checkbox-input').attr('checked', false);
        }
      });
    });
  </script>
@endsection