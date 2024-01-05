@extends('layouts.admin.master')
@section('content')

<div class="flex flex-row justify-content-center">
    <form action="/admin/reports/exports">
        @csrf
        @method('GET')
        
        <input type="submit" role="button" value="Pencet" class="btn btn-success">
    </form>
</div>

@endsection