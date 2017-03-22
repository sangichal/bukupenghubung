@extends('main')

@section('nav')
	@include('partials._nav')
@endsection

@section('content')

<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<h1 class="panel-title pull-left">SEMUA DISKUSI KELAS {{Auth::User()->classroom->nama_kelas}}</h1>
		<div class="pull-right">
			@if( ( Auth::User()->level == 'admin' || Auth::User()->level == 'guru' ) && count(Auth::User()->classroom) > 0 )
				<a href="{{route('diskusi.create')}}" class="btn btn-primary">Buat Diskusi</a>
			@endif
		</div>
	</div>
	<div class="panel-body">
		@foreach (['danger', 'warning', 'success', 'info'] as $msg)
			@if(Session::has('alert-' . $msg))
				<div class="alert alert-dismissible alert-{{ $msg }}">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					{{ Session::get('alert-' . $msg) }}
				</div>
			@endif
		@endforeach
		<table id="datatable_diskusi" class="table table-hover table-bordered">
			<thead>
				<tr>
					<th class="text-center valign-middle">
						#
					</th>
					<th width="30%">Judul</th>
					<th width="20%">Siswa</th>
					<th>Dibuat</th>
					<th>Update terakhir</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach( $data['discussions'] as $discussion )
					<tr>
						<td class="text-center valign-middle">-</td>
						<td data-search="{{$discussion->judul_diskusi}}">
							<a href="{{route('diskusi.show', $discussion->id)}}" title="Lihat diskusi">{{ $discussion->judul_diskusi }}</a>
						</td>
						<td data-search="{{$discussion->student->nama}}"><a href="{{route('diskusi.student', $discussion->student->id)}}" title="Lihat diskusi dengan {{$discussion->student->nama}}">{{$discussion->student->nama}}</a></td>
						<td data-order="{{ $discussion->created_at }}">{{ Helper::humantime( $discussion->created_at ) }}</td>
						<td data-order="{{ $discussion->updated_at }}">{{ empty($discussion->updated_at) ? '-' : Helper::humantime( $discussion->updated_at ) }}</td>
						<td>
							@if(Auth::User()->level == 'admin' || Auth::User()->id == $discussion->id_wali_kelas)
								<form style="display: inline;" action="{{route('diskusi.destroy', $discussion->id)}}" method="POST" class="deleteForm">
									<input type="hidden" name="_method" value="DELETE">
									{{ csrf_field() }}
									<button type="submit" title="Hapus diskusi" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
								</form>
							@else 
								-
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection