@extends('layouts.adminlte-app')

@section('title', 'Book Category Management')

@push('styles')
@endpush

@push('scripts')
    <script>
        function loadBookCategory() {
            fetch('/book-category/list')
                .then(res => res.json())
                .then(data => {
                    let fill = '';
                    data.forEach(p => {
                        fill += `<tr><td>${p.name}</td><td>${p.code}</td><td>${p.description || '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="openModal(${p.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="openDelete(${p.id})">Delete</button>
                                </td></tr>`;
                    });
                    document.getElementById('bookCategory').innerHTML = fill;
                });
        }

        function openModal(id = null) {
            const form = document.getElementById('formBookCategory');
            form.reset();
            form.classList.remove('was-validated');
            document.getElementById('error').classList.add('d-none');
            document.getElementById('bookCategoryId').value = '';
            if (id) {
                fetch(`/book-category/${id}`).then(r => r.json()).then(d => {
                    form.name.value = d.name;
                    form.code.value = d.code;
                    form.description.value = d.description;
                    document.getElementById('bookCategoryId').value = d.id;
                });
            }
            $('#modalBookCategory').modal('show');
        }

        document.getElementById('formBookCategory').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            form.classList.add('was-validated');
            if (!form.checkValidity()) return;

            const id = document.getElementById('bookCategoryId').value;
            const data = {
                name: form.name.value,
                code: form.code.value,
                description: form.description.value,
            };

            fetch(id ? `/book-category/${id}` : '/book-category', {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            }).then(async r => {
                if (!r.ok) {
                    const msg = await r.text();
                    document.getElementById('error').textContent = msg;
                    document.getElementById('error').classList.remove('d-none');
                    return;
                }
                $('#modalBookCategory').modal('hide');
                loadBookCategory();
            });
        });

        function openDelete(id) {
            if (!confirm('Delete Data?')) return;
            fetch(`/book-category/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => loadBookCategory());
        }

        loadBookCategory();
    </script>
@endpush

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">@yield('title') STUDENT</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Featured</h5>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-primary mb-3" onclick="openModal()">Add Book Category</button>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody id="bookCategory"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
            <div class="modal fade" id="modalBookCategory">
                <div class="modal-dialog">
                    <form id="formBookCategory" class="modal-content needs-validation" novalidate>
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Book Category</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="bookCategoryId">
                            <div class="form-group">
                                <label>Name</label>
                                <input name="name" type="text" class="form-control" required>
                                <div class="invalid-feedback">Name needed</div>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input name="code" type="text" class="form-control" required>
                                <div class="invalid-feedback">Code needed</div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control"></textarea>
                                <div class="invalid-feedback">Description needed</div>
                            </div>
                            <div id="error" class="alert alert-danger d-none"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
@endsection
