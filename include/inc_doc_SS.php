<button hidden class="btn bg-danger text-light mb-3" data-toggle="modal" data-target="#DeleteModal"><i class="fas fa-trash-alt"></i> Remove Document</button>

<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="../admin/doc_removal.php" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">WARNING</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to completely remove this document from the database. are you sure you want to do this?
                </div>
                <div class="modal-footer">
                    <input hidden name="doc_id" value='<?php echo $doc_id ?>'>
                    <input hidden name="doc_type" value='<?php echo $doc_type ?>'>
                    <button type="submit" class="btn btn-danger">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>