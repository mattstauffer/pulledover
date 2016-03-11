@extends('layouts.app')

@section('content')
    <div
            id="admin-dashboard"
            data-users="{{ $users->toJson() }}">
    </div>
@endsection

@section('scripts')
    <script src="/js/admin-dashboard.js"></script>
@stop