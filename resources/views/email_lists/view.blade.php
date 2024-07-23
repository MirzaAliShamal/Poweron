<div class="form-group">
    <label class="form-label">Name</label>
    <p>{{ $emailList->name }}</p>
</div>
<div class="form-group">
    <label class="form-label">Active/Unsubscribed Subscribers</label>
    <p>{{ $emailList->activeSubscribers->count().'/'.$emailList->inActiveSubscribers->count() }}</p>
</div>
<div class="form-group">
    <label class="form-label">Created on</label>
    <p>{{ $emailList->created_at }}</p>
</div>
