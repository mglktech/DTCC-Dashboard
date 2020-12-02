<?php
if (isset($_POST['leaveNote'])) {
    $time = time();
    $doc_type = $_POST["doc_type"];
    $doc_id = $_POST['doc_id'];
    $steam_id = $_POST['signed_by'];
    $message = quotefix($_POST['message']);
    $sql = "INSERT INTO private_notes (`doc_id`, `doc_type`, `steam_id`, `timestamp`, `message`) VALUES ('$doc_id','$doc_type','$steam_id','$time','$message')";
    Query($sql);
}

function prepareNotes($doc_id, $doc_type)
{
    $sql = "SELECT * FROM `notes` WHERE `doc_id` = '$doc_id' AND `doc_type` = '$doc_type' ORDER BY `timestamp` DESC";
    return Query($sql);
}

function CreateNotesTable($doc_id, $doc_type)
{
    $str_heap = array();
    $tbl_head = ["Notes"];
    $data = prepareNotes($doc_id, $doc_type);
    if ($data) {


        foreach ($data as $row) {
            $str_stack = array();
            $str = $row->char_name . ": " . $row->message . " - " . ToDateS($row->timestamp);
            $str_stack[] = $str;
            $str_heap[] = $str_stack; // one dimensional array...
        }
    } else {
        $str_heap = [["No Notes..."]];
    }

    return Tablefy($tbl_head, $str_heap);
}

?>
<div class="modal fade" id="NoteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="" method="post">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adding note for <?php echo $char_name ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Leave a Note for other supervisors to see!</h6>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <textarea name="message" class="form-control" aria-label="With textarea"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input name="leaveNote" value="1" hidden></input>
                    <input name="signed_by" value="<?php echo $_SESSION["steam_id"]; ?>" hidden></input>
                    <input name="doc_type" value=<?php echo $doc_type ?> hidden></input>
                    <input name="doc_id" value="<?php echo $doc_id; ?>" hidden></input>
                    <button type="submit" class="btn btn-success">Leave Note</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                </div>
            </div>
        </div>
    </form>
</div>