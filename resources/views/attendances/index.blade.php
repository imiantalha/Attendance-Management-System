 @extends('layouts.master')

 @section('content')
 <div class="row">
     <div class="col-lg-12 margin-tb mb-4">
         <div class="pull-left">
             <h2>Attendance
                 <div class="float-end">
                     @can('product-create')
                     <a class="btn btn-success" href="{{ route('attendances.create') }}"> Mark Attendance</a>
                     
                     @endcan
                 </div>
             </h2>
         </div>
     </div>
 </div>


 @if ($message = Session::get('success'))
 <div class="alert alert-success">
     <p>{{ $message }}</p>
 </div>
 @endif

 <table class="table table-striped table-hover">
     <tr>
         <th>User Name</th>
         <th>Start Time</th>
         <th>End Time</th>
         <th>Date</th>
         <th>Attendance By</th>
         <th width="350px">Action</th>
     </tr>
     @foreach ($attendances as $attendance)
     <tr>
         <td>{{ $attendance->user->name }}</td> 
         <td>{{ \Carbon\Carbon::parse($attendance->start_time)->format('h:i A') }}</td>
         <td>{{ \Carbon\Carbon::parse($attendance->end_time)->format('h:i A') }}</td>
         <td>{{ $attendance->attendance_date }}</td>
         <td>{{ $attendance->attendedBy->name }}</td>
         <td>
             <form action="{{ route('attendances.destroy',$attendance->id) }}" method="POST">
                 <a class="btn btn-info" href="{{ route('attendances.show',$attendance->id) }}">Show</a>
                 @can('product-edit')
                 <a class="btn btn-primary" href="{{ route('attendances.edit',$attendance->id) }}">Edit</a>
                 @endcan
                 <a class="btn btn-success" href="{{ route('attendances.report', $attendance->user->id) }}"> Report</a>

                 @csrf
                 @method('DELETE')
                 @can('product-delete')
                 <button type="submit" class="btn btn-danger">Delete</button>
                 @endcan
             </form>
         </td>
     </tr>
     @endforeach
 </table>
 <nav aria-label="Page navigation">
    {{ $attendances->links('pagination::bootstrap-4') }}
</nav>

 @endsection