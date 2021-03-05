<?php

function CollectQuestionnaireData()
{
    $sql = "SELECT * FROM `practical_test_data_v0` WHERE 1";
    return fetchAll($sql);
}

function CreateQuestionnaireElement($id, $data)
{

    $vis_id = $id + 1;

    echo "<div class='container-fluid'>";
    echo "<div class='row bg-light px-3 pt-0'>";
    echo "<div class='row w-100 mt-3 mb-3 rounded-lg'>";
    echo "<div class='col'>";
    echo "<h5 class='mt-3'>" . $vis_id . ". " . $data[$id][1] . "</h5>";
    echo "<i><h6>" . $data[$id][2] . "</h6></i><br>";
    echo "<div class='test-question input-group mb-3'>
  <input type='range' min='0' max='5' value='0' step='1' name='A[]' oninput='UpdateElem(this)' class='slider w-25' id='RangeSlider'>
  <div class='input-group-append'>
    <input class='output w-25' type='text' value='0' disabled>
  </div>
  
</div>
</div>
</div>
</div>
</div><hr>";
}

function CollectCallsigns($rank, $region)
{
    $sql = "SELECT * FROM `callsigns` WHERE `min_rank` = $rank AND region = '$region' AND assigned_steam_id is null limit 10";

    return fetchAll($sql);
}

?>

<div class="container-fluid">
    <div class="row">
        <h1>Practical Test: <?php echo $char_name ?></h1>
        <h5 class="w-100 font-italic mb-3 font-weight-normal">Let's get dis beautiful person on the road!!!</h5>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <form class="w-100" action="view_test.php" method="post">
            <?php $data = CollectQuestionnaireData();
            for ($i = 0; $i < count($data); $i++) {
                CreateQuestionnaireElement($i, $data);
            } ?>
            <div class="form-group col-3 px-0">
                <label for="callsign">
                    <h3>Select Callsign</h3>
                </label>
                <select name="callsign" class="form-control" id="callsign" required>
                    <?php $callsigns = CollectCallsigns(0, $region);
                    foreach ($callsigns as $c) {
                        echo "<option>" . $c[0] . "</option>";
                    }

                    ?>
                </select>
            </div>
            <input name="steamid" value="<?php echo $student_steamid ?>" hidden>
            <input name="char_name" value="<?php echo $char_name ?>" hidden>
            <input name="test_type" value="practical" hidden>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Comments</span>
                </div>
                <textarea name="comments" rows="5" class="form-control" aria-label="With textarea"></textarea>
            </div>
            <button class="btn btn-success ml-3 mt-2 mb-5" type="submit" <?php if ($_SESSION["rank"] < 2) echo "disabled" ?>>Submit</button>
            <a class="btn btn-secondary mt-2 mb-5" href="table_tests.php">Go Back</a>
        </form>
    </div>
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