<form method="POST" action="{{ $deleteUrl }}" class="d-inline-flex"
      onsubmit="return confirm('Delete this payment record? Its amount will be removed from collections and the unpaid balance will increase. You can undo this deletion.');">
    @csrf
    @method('DELETE')
    <input type="hidden" name="return" value="{{ $deleteReturn }}">
    <button type="submit" class="btn btn-outline-danger" aria-label="Delete payment" title="Delete payment">
        <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete
    </button>
</form>
