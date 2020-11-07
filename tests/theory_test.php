<?php



function CollectQuestionnaireData()
{
    $sql = "SELECT * FROM `theory_test_data_v0` WHERE 1";
    return fetchAll($sql);
}

function CreateQuestionnaireElement($id, $data)
{

    $vis_id = $id + 1;

    echo "<div class='container'>";
    echo "<div class='row border mt-3 mb-3 rounded-lg'>";
    echo "<div class='col'>";
    echo "<h5 class='mt-3'>" . $vis_id . ". " . $data[$id][1] . "</h5>";
    echo "<i>Expected Answer:</i> <h6>" . $data[$id][2] . "</h6><br>";
    echo "<div class='input-group mb-3'>
  <input type='range' min='0' max='5' value='0' step='1' name='A[]' oninput='UpdateElem(this)' class='slider w-25' id='RangeSlider'>
  <div class='input-group-append'>
    <input class='output w-25' type='text' value='0' disabled>
  </div>
  
</div>
</div>
</div>
</div>";
}
?>
<div class="container border">
    <h3> Theory Test: <?php echo $char_name ?></h3>
    <p class="">
        To begin, you will be asking your applicant the following theoretical
        questions. These should be common sense. Make sure to mark each answer
        accordingly.
    </p>
</div>

<div class="container border">
    <form action="view_test.php" method="post">
        <?php $data = CollectQuestionnaireData();
        for ($i = 0; $i < count($data); $i++) {
            CreateQuestionnaireElement($i, $data);
        } ?>
        <input name="steamid" value="<?php echo $student_steamid ?>" hidden>
        <input name="char_name" value="<?php echo $char_name ?>" hidden>
        <input name="test_type" value="theory" hidden>
        <button class="btn btn-success" type="submit">Submit</button>
        <a class="btn btn-secondary" href="table_tests.php">Go Back</a>
    </form>
</div>


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