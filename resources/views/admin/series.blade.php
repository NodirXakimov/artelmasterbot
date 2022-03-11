@extends('layouts.master')

@section('additional_styles')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endsection

@section('additional_scripts')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(function(){
            $('.show-inners').on('click', function (){
                let inners = JSON.parse($(this).attr("data-inner"))
                let innersText = "";
                inners.forEach(function (value, key){
                    innersText += `<li class="list-group-item">${value.seria}</li>`
                })
                $('#inners-list').html(innersText)
                $('#outerOnModal').html($(this).attr("data-outer"))
                $('#showInnerSeriesModal').modal('show');
            });

        });
    </script>
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
                                    <button type="button" class="btn btn-outline-primary btn-sm show-inners" data-outer="{{ $outer->seria }}" data-inner="{{ $outer->inners }}"><i class="bi bi-eye-fill"></i></button>
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
    <!-- Start modals -->

    <!-- Show inner series modal -->
    <div class="modal fade" id="showInnerSeriesModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inners of <b id="outerOnModal"></b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-numbered" id="inners-list">

                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
{{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div>
        </div>
    </div>

    <!-- End modals -->

@endsection
