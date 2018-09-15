@extends('layouts.admin')
@section('content')
  <div class="content-main-block mrg-t-40">
    <div class="admin-create-btn-block">
      <a href="{{route('tvseries.create')}}" class="btn btn-danger btn-md"><i class="material-icons left">add</i> Create TvSeries</a>
      <!-- Delete Modal -->
      <a type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#bulk_delete"><i class="material-icons left">delete</i> Delete Selected</a>   
      @if (Session::has('changed_language'))
        <a href="{{ route('tmdb_tv_translate') }}" class="btn btn-danger btn-md"><i class="material-icons left">translate</i> Translate all to {{Session::get('changed_language')}}</a>   
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
              {!! Form::open(['method' => 'POST', 'action' => 'TvSeriesController@bulk_delete', 'id' => 'bulk_delete_form']) !!}
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
            <th>Tv Series Title</th>
            <th>Rating</th>
            <th>By TMDB</th>
            <th>Featured</th>
            <th>Actions</th>
          </tr>
        </thead>
        @if ($tv_serieses)
          <tbody>
            @foreach ($tv_serieses as $key => $tv_series)
              <tr>
                <td>
                  <div class="inline">
                    <input type="checkbox" form="bulk_delete_form" class="filled-in material-checkbox-input" name="checked[]" value="{{$tv_series->id}}" id="checkbox{{$tv_series->id}}">
                    <label for="checkbox{{$tv_series->id}}" class="material-checkbox"></label>
                  </div>
                  {{$key+1}}
                </td>
                <td><img
                  @if ($tv_series->thumbnail != null)
                    src="{{asset('/images/tvseries/thumbnails/' . $tv_series->thumbnail)}}"
                  @elseif(isset($tv_series->seasons[0]) && $tv_series->seasons[0]->thumbnail != null)
                    src="{{asset('/images/tvseries/thumbnails/' . $tv_series->seasons[0]->thumbnail)}}"  
                  @elseif ($tv_series->poster != null)
                    src="{{asset('/images/tvseries/posters/' . $tv_series->poster)}}"
                  @else
                    src="http://via.placeholder.com/70x70"
                  @endif
                    alt="Pic" width="70px" class="img-responsive">
                </td>
                <td>{{$tv_series->title}}</td>
                <td>
                  IMDB {{$tv_series->rating}}
                </td>
                <td>
                  @if ($tv_series->tmdb == 'Y')
                    <i class="material-icons done">done</i>
                  @else
                    -
                  @endif
                </td>
                <td>
                  {!!$tv_series->featured == 1 ? '<i class="material-icons done">done</i>' : '-'!!}
                </td>
                <td>
                  <div class="admin-table-action-block"> 
                    @if (count($tv_series->seasons) > 0)
                      <a href="{{url('show/detail', (isset($tv_series->seasons[0]) ? $tv_series->seasons[0]->id : ''))}}" data-toggle="tooltip" data-original-title="Page Preview" target="_blank" class="btn-default btn-floating"><i class="material-icons">desktop_mac</i></a>
                    @else
                      <a href="#" data-toggle="tooltip" data-original-title="Please add seasons from manage seasons" class="btn-default btn-floating disabled"><i class="material-icons">desktop_mac</i></a>
                    @endif
                    <a href="{{route('tvseries.edit', $tv_series->id)}}" data-toggle="tooltip" data-original-title="Edit" class="btn-info btn-floating"><i class="material-icons">mode_edit</i></a>
                    <a href="{{route('tvseries.show', $tv_series->id)}}" data-toggle="tooltip" data-original-title="Manage Seasons" class="btn-success btn-floating"><i class="material-icons">settings</i></a>
                    <button type="button" class="btn-danger btn-floating" data-toggle="modal" data-target="#{{$tv_series->id}}deleteModal"><i class="material-icons">delete</i> </button>
                  </div>
                </td>
              </tr>
              <!-- Delete Modal -->
                <div id="{{$tv_series->id}}deleteModal" class="delete-modal modal fade" role="dialog">
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
                        {!! Form::open(['method' => 'DELETE', 'action' => ['TvSeriesController@destroy', $tv_series->id]]) !!}
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