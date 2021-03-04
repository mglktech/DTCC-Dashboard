<?php



function CollectQuestionnaireData()
{
    $sql = "SELECT * FROM test_questions WHERE type = 'theory' and version = '1' ORDER BY q_number";
    return Query($sql);
}

function CreateQuestionnaireElement($id, $data)
{

    $vis_id = $id + 1;
    $points = $data[$id]->max_points;
    echo "<div class='container-fluid'>";
    echo "<div class='row px-3 pt-0'>";
    echo "<div class='row w-100 mt-3 mb-3 rounded-lg'>";
    echo "<div class='col'>";
    echo "<ul><li>" . $vis_id . ". " . $data[$id]->question . "<ul>";
    echo "<li>" . $data[$id]->expected_answer . "</li></ul></li></ul>";
    echo "<div class='test-question input-group mb-3' >
  <input type='range' min='0' max='" . $points . "' value='0' step='1' name='A[]' oninput='UpdateElem(this)' class='slider w-25' id='RangeSlider'>
  <div class='input-group-append'>
    <input class='ml-2 output w-25' type='text' value='0' disabled>
  </div>
  
</div>
</div>
</div>
</div>
</div><hr>";
}
?>
<div class="container">
        <p class="text-right">Student: <?php echo $char_name ?><br>Discord: <?php echo $discord_name ?></p>
</div>
<section>

        
<div class="container">
            <h3 class="text-center">Theory Test</h3>
            <p class="text-left"><em>Next, you will be asking your applicant the following theoretical questions. These should be common sense. Make sure to mark each answer accordingly.</em><br></p>
</div>

<div class="container">
    <div class="row">
        <form action="view_test.php" method="post">
            <?php $data = CollectQuestionnaireData();
            for ($i = 0; $i < count($data); $i++) {
                CreateQuestionnaireElement($i, $data);
            } ?>
            <input name="steamid" value="<?php echo $student_steamid ?>" hidden>
            <input name="char_name" value="<?php echo $char_name ?>" hidden>
            <input name="test_type" value="theory" hidden>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Comments</span>
                </div>
                <textarea name="comments" rows="5" class="form-control" aria-label="With textarea"></textarea>
            </div>
            <button class="btn btn-success mb-5 mr-2 mt-3" type="submit" <?php if ($_SESSION["rank"] < 2) echo "disabled" ?>>Submit</button>
            <a class="btn btn-secondary mb-5 mr-2 mt-3" href="table_tests.php">Go Back</a>
        </form>
    </div>
</div>
</section>

<script>
    function IndexInClass(elem) {
        var sliders = document.getElementsByClassName("slider");
        var num = 0;
        for (var i = 0; i < sliders.length; i++) {
            if (sliders[i] === elem) {
                return i;
            }
        }
        return -1;
    }


    function UpdateElem(elem) {
        var sliders = document.getElementsByClassName("slider");
        var index = IndexInClass(elem);
        var output = document.getElementsByClassName("output")[index];
        output.value = elem.value;
    }

    // Update the current slider value (each time you drag the slider handle)
</script>