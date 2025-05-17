@extends('layouts.adminlte-app')
@section('title', 'Senarai Pelajar')
@section('page_title', 'Pelajar')

@section('content')
<button class="btn btn-primary mb-3" onclick="bukaModal()">Tambah Pelajar</button>
<table class="table table-bordered">
  <thead><tr><th>Nama</th><th>Emel</th><th>Program</th><th>Tindakan</th></tr></thead>
  <tbody id="senarai"></tbody>
</table>

<div class="modal fade" id="modalPelajar">
  <div class="modal-dialog">
    <form id="borangPelajar" class="modal-content needs-validation" novalidate>
      @csrf
      <div class="modal-header bg-primary text-white"><h5 class="modal-title">Pelajar</h5></div>
      <div class="modal-body">
        <input type="hidden" id="pelajarId">
        <div class="form-group">
          <label>Nama</label>
          <input name="nama" type="text" class="form-control" required>
          <div class="invalid-feedback">Nama wajib diisi</div>
        </div>
        <div class="form-group">
          <label>Emel</label>
          <input name="emel" type="email" class="form-control" required>
          <div class="invalid-feedback">Emel sah diperlukan</div>
        </div>
        <div class="form-group">
          <label>Program</label>
          <input name="program" type="text" class="form-control" required>
          <div class="invalid-feedback">Sila isikan program</div>
        </div>
        <div id="ralat" class="alert alert-danger d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function bukaModal(id = null) {
  const form = document.getElementById('borangPelajar');
  form.reset();
  form.classList.remove('was-validated');
  document.getElementById('ralat').classList.add('d-none');
  document.getElementById('pelajarId').value = '';
  if (id) {
    fetch(`/pelajar/${id}`).then(r => r.json()).then(d => {
      form.nama.value = d.nama;
      form.emel.value = d.emel;
      form.program.value = d.program;
      document.getElementById('pelajarId').value = d.id;
    });
  }
  $('#modalPelajar').modal('show');
}

document.getElementById('borangPelajar').addEventListener('submit', function(e) {
  e.preventDefault();
  const form = this;
  form.classList.add('was-validated');
  if (!form.checkValidity()) return;

  const id = document.getElementById('pelajarId').value;
  const data = {
    nama: form.nama.value,
    emel: form.emel.value,
    program: form.program.value
  };

  fetch(id ? `/pelajar/${id}` : '/pelajar', {
    method: id ? 'PUT' : 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify(data)
  }).then(async r => {
    if (!r.ok) {
      const msg = await r.text();
      document.getElementById('ralat').textContent = msg;
      document.getElementById('ralat').classList.remove('d-none');
      return;
    }
    $('#modalPelajar').modal('hide');
    loadPelajar();
  });
});

function loadPelajar() {
  fetch('/pelajar/list')
    .then(res => res.json())
    .then(data => {
      let isi = '';
      data.forEach(p => {
        isi += `<tr><td>${p.nama}</td><td>${p.emel}</td><td>${p.program}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="bukaModal(${p.id})">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="padam(${p.id})">Padam</button>
          </td></tr>`;
      });
      document.getElementById('senarai').innerHTML = isi;
    });
}

function padam(id) {
  if (!confirm('Padam pelajar ini?')) return;
  fetch(`/pelajar/${id}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
  }).then(() => loadPelajar());
}

loadPelajar();
</script>
@endpush
