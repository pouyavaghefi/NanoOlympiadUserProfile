<div class="row clearfix">
    <div class="comment-form mt-4">
        <form action="" method="POST">
            @csrf
            <div class="mb-3">
                <label for="comment" class="form-label">Leave a comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>