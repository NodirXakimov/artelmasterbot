@extends('layouts.master')

@section('additional_styles')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endsection

@section('additional_scripts')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('content')



    <div class="pagetitle">
        <h1>AC series</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-sm table-hover datatable">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Outer series</th>
                            <th scope="col">Count of inners</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Updated at</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outers as $outer)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $outer->seria }}</td>
                                <td><span class="badge bg-secondary">{{ $outer->inners->count() }}</span></td>
                                <td>{{ $outer->created_at }}</td>
                                <td>{{ $outer->updated_at }}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye-fill"></i></button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil-square"></i></button>
                                    <button type="button" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>

@endsection
