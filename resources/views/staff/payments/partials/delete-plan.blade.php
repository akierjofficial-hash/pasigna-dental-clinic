<form method="POST" action="{{ route('staff.installments.destroy', $plan) }}" class="d-inline-flex"
      onsubmit="return confirm('Delete this installment plan and remove its receipts from active payment lists and collections? You can undo this deletion. The patient and linked visit will remain; any treatment charge on that visit may become payable separately.');">
    @csrf
    @method('DELETE')
    <input type="hidden" name="return" value="{{ $deletePlanReturn }}">
    <button type="submit" class="btn btn-outline-danger" title="Delete installment plan">
        <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete Plan
    </button>
</form>
