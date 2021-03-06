<?php function CreateQuestionnaireElement($id, $data)
{
    $vis_id = $id + 1;
    $points = $data->max_points;
    $question = $data->question;
    $answer = $data->question_desc;
    include "elems/question_practical.php";
}

?>

<div class="container">
        <p class="text-right">Student: <?php echo $student->char_name ?><br>Discord: <?php echo $student->discord_name ?></p>
</div>
<section>

        
<div class="container">
            <h3 class="text-center">Practical Test</h3>
            <p class="text-center"><em>Prrrrrrrrrrrrrrrractical Test - Sky Baca</em><br></p>
</div>

<div class="container">
    
        <form action="test_results.php" method="post">
            <?php 
            for ($i = 0; $i < count($test_data); $i++) {
                CreateQuestionnaireElement($i, $test_data[$i]);
            } ?>
            <input name="steamid" value="<?php echo $student->steam_id ?>" hidden>
            <input name="test_type" value="practical" hidden>
            <input name="test_version" value = "0" hidden>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Comments</span>
                </div>
                <textarea name="comments" rows="5" class="form-control" aria-label="With textarea"></textarea>
            </div>
            <button class="btn btn-success mb-5 mr-2 mt-3" type="submit" <?php if (!Rank("Supervisor")) echo "disabled" ?>>Submit</button>
            <a class="btn btn-secondary mb-5 mr-2 mt-3" href="table_needs_practical.php">Go Back</a>
        </form>
    
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