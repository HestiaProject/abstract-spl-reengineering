@extends('projects.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Team Members of project: {{ $project->title }}</h2>
        </div>

    </div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger">
    <p>{{ $message }}</p>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif




<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('projects.teams.store' , $project->id) }}" method="post">
    @csrf
    <div class="input-group">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label> <strong>Email:</strong>
                        <input name="email" type="text" class="form-control" placeholder="User email" aria-label="Search" aria-describedby="basic-addon2">
                    </label>
                    
                    <label><strong>Role:</strong>
                        <select class="custom-select" name="role">
                            <option value="Admin">Admin</option>
                            <option value="Team Member">Team Member</option>
                            <option value="External User">External User</option>
                        </select>
                    </label>

                    <input type="hidden" id="project_id" name="project_id" value=" {{ $project->id }}">

                    <button type="submit" class="btn btn-primary">Add Member <i class="fas fa-plus"></i></button>

                </div>

            </div>
        </div>
    </div>

</form>
<a class="btn btn-primary btn-warning" href="{{action('TeamController@generateDocx',$project)}}">Download Team Report <i class="fas fa-file-download"></i></a>
<br><br>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="100" style="width:100%">
                <tr>

                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="280px">Action</th>
                </tr>

                @foreach ($project->teams as $team)
                <tr>

                    <td>{{ $team->user->name }}</td>
                    <td>{{ $team->role }}</td>
                    @if($team->status == 'Incomplete')
                    <td style="color:red;">{{ $team->status }}</td>
                    @else

                    <td style="color:green;">{{ $team->status }}</td>

                    @endif
                    <td>
                        <form action="{{ route('projects.teams.destroy', ['team'=>$team->id,'project'=>$project->id]) }}" method="post">

                            <a class="btn btn-info" href="{{ route('projects.teams.edit', ['project'=>$project->id, 'team'=>$team->id]) }}">View <i class="fas fa-eye"></i></a>

                            @csrf
                            @method('DELETE')
                            @if ($project->owner_id != $team->user_id && $team->current_user())
                            <button type="submit" class="btn btn-danger">Remove <i class="fas fa-trash"></i></button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>
</div>
@endsection