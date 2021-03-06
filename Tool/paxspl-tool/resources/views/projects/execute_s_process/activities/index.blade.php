@extends('projects.app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Execute Scoping Activities: {{ $project->title }}</h2>
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



<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        @if ($project->status_user())
        <div class="alert alert-danger">
            Before continuing, all team members information must be completed!<br><br>
            <a class="collapse-item" href="{{ route('projects.teams.index', $project -> id) }}">Collect Team Information</a>
        </div>
        @elseif (!$project->retriever())
        <div class="alert alert-danger">
            You Must be a feature retriver to execute the process!<br><br>

        </div>
        @elseif ($project->artifacts->count()==0)
        <div class="alert alert-danger">
            Before continuing, at least one artifact must be created!<br><br>
            <a class="collapse-item" href="{{ route('projects.artifact.index', $project -> id) }}">Register Artifacts</a>
        </div>
        @elseif ($project->techniques_project->count()==0)
        <div class="alert alert-danger">
            Before continuing, at least one technique must be added to the project!<br><br>
            <a class="collapse-item" href="{{ route('projects.technique_projects.index', $project -> id) }}">Add Techniques</a>
        </div>
        @elseif ($project->assemble_process->count()==0)
        <div class="alert alert-danger">
            Before continuing, at least one technique must be added to the project!<br><br>
            <a class="collapse-item" href="{{ route('projects.technique_projects.index', $project -> id) }}">Add Techniques</a>
        </div>
        @else

        <a class="btn btn-primary btn-warning" href="{{action('ExecuteActivitySProcessController@generateDocx',['project' => $project, 'execute_s_process' => $execute_s_process])}}">Download Report <i class="fas fa-file-download"></i></a>
        <br><br>
        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"> Progress:</h6>
            </div>
            <div class="card-body">
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width:  {{$execute_s_process->progress()}}%; background-color:{{$execute_s_process->progress_color()}};" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        {{$execute_s_process->progress()}}%
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <a href="#collapseCanvas" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">

                <h6 class="m-0 font-weight-bold text-primary">Scoping Process:</h6>
            </a>

            <!-- Card Content - Collapse -->
            <div class="collapse" id="collapseCanvas" style="">
                <div class="card-body">
                    <div class="canvas">
                        <div id="js-canvas"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#collapseCard" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">

                <h6 class="m-0 font-weight-bold text-primary">Doing:</h6>
            </a>

            <!-- Card Content - Collapse -->
            <div class="collapse" id="collapseCard" style="">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="100" style="width:100%">
                            <tr>

                                <th>Name</th>
                                <th>Phase</th>
                                <th>Order</th>
                                <th>Problems</th>

                                <th width="320px">Action</th>
                            </tr>

                            @foreach ($execute_s_process->activities_retrieval_doing as $activity)
                            <tr>

                                <td>{{ $activity->name }}</td>
                                <td>{{ $activity->phase }}</td>
                                <td>{{ $activity->order }}</td>

                                <td>{{ $activity->problems_found() }}</td>




                                <td>

                                    <form action="{{ route('projects.execute_s_process.activities.destroy', ['project'=>$project->id,'execute_s_process'=>$execute_s_process->id,'activity'=>$activity->id]) }}" method="POST">



                                        <a class="btn btn-info " href="{{ route('projects.execute_s_process.activities.edit', ['project'=>$project->id,'execute_s_process'=>$execute_s_process->id,'activity'=>$activity->id]) }}">Continue <i class="fas fa-play"></i> </a>

                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#collapseCard3" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">

                <h6 class="m-0 font-weight-bold text-primary">To Do:</h6>
            </a>


            <!-- Card Content - Collapse -->
            <div class="collapse" id="collapseCard3" style="">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="100" style="width:100%">
                            <tr>

                                <th>Name</th>
                                <th>Phase</th>
                                <th>Order</th>

                                <th width="320px">Action</th>
                            </tr>

                            @foreach ($execute_s_process->activities_retrieval_todo as $activity)
                            <tr>

                                <td>{{ $activity->name }}</td>
                                <td>{{ $activity->phase }}</td>
                                <td>{{ $activity->order }}</td>





                                <td>

                                    <form action="{{ route('projects.execute_s_process.activities.destroy', ['project'=>$project->id,'execute_s_process'=>$execute_s_process->id,'activity'=>$activity->id]) }}" method="POST">



                                        <a class="btn btn-info " href="{{ route('projects.execute_s_process.activities.edit', ['project'=>$project->id,'execute_s_process'=>$execute_s_process->id,'activity'=>$activity->id]) }}">Execute <i class="fas fa-play"></i> </a>

                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#collapseCard2" class="d-block card-header py-3 collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseCardExample">

                <h6 class="m-0 font-weight-bold text-primary">Done:</h6>
            </a>

            <!-- Card Content - Collapse -->
            <div class="collapse" id="collapseCard2" style="">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="100" style="width:100%">
                            <tr>

                                <th>Name</th>
                                <th>Phase</th>
                                <th>Order</th>

                                <th width="320px">Action</th>
                            </tr>

                            @foreach ($execute_s_process->activities_retrieval_done as $activity)
                            <tr>

                                <td>{{ $activity->name }}</td>
                                <td>{{ $activity->phase }}</td>
                                <td>{{ $activity->order }}</td>





                                <td>

                                    <form action="{{ route('projects.execute_s_process.activities.destroy', ['project'=>$project->id,'execute_s_process'=>$execute_s_process->id,'activity'=>$activity->id]) }}" method="POST">



                                        <a class="btn btn-info " href="{{ route('projects.execute_s_process.activities.edit', ['project'=>$project->id,'execute_s_process'=>$execute_s_process->id,'activity'=>$activity->id]) }}">Continue <i class="fas fa-play"></i> </a>

                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            @endif
        </div>

    </div>

</div>
<script>
    var diagramUrl = <?php
                        $string = preg_replace('/\\s\\s+/', ' ', ($execute_s_process->diagram));
                        echo $string ?>;
    var viewer = new BpmnJS({
        container: $('#js-canvas'),
        height: 500
    });

    function openDiagram(bpmnXML) {

        // import diagram
        viewer.importXML(bpmnXML, function(err) {

            if (err) {
                return console.error('could not import BPMN 2.0 diagram', err);
            }

            // access modeler components
            var canvas = viewer.get('js-canvas');


            // zoom to fit full viewport
            canvas.zoom('fit-viewport');


        });
    }


    // load external diagram file via AJAX and open it
    openDiagram(diagramUrl);
</script>
@endsection