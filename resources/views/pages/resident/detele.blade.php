
<!-- Modal -->
<div class="modal fade" id="exampleModal-{{ item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="/resident/{{$item->id}} " method="post">
        @csrf
        @method('DELETE')
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Delete</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span>Apakah anda yakin akan menghapus data ini</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-outline-danger">Ya, hapus!</button>
      </div>
    </div>
    </form>
 
  </div>
</div>