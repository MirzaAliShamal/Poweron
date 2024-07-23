<div class="form-group">
    <label class="form-label">Name</label>
    <p>{{ $subscriber->name }}</p>
</div>
<div class="form-group">
    <label class="form-label">Email</label>
    <p>{{ $subscriber->email }}</p>
</div>
<div class="form-group">
    <label class="form-label">Email List</label>
    <p>{{ $subscriber->emailList->name }}</p>
</div>
<div class="form-group">
    <label class="form-label">Created on</label>
    <p>{{ $subscriber->created_at }}</p>
</div>
