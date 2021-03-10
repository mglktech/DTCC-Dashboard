<?php
include "../../include/components/head.php";


isset($_SESSION["steam_id"]) ? $id = $_SESSION["steam_id"] : $id = null;
isset($_GET["id"]) ? $student_id = $_GET["id"] : $student_id = null;
$instr = q_fetchPlayer($id);
$student = q_fetchPlayer($student_id);


?>
<section>
    <div class="container">
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h1>Day One</h1>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h3>Instructor Checklist</h3>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h6>Author: Isaac Mattis</h6>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Instructor: <?= $instr->char_name; ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Student: <?= $student->char_name ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h6 class="text-center">Ensure that you cover the following subjects:</h6>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Everyday work</h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <ul>
                    <li>Do they have the 3 items that is needed to clock on. (painkiller, bandage, repair kit)</li>
                    <li>Tell the trainee where to get the items needed (LTD's)</li>
                    <li>No illegal substance on you or in your system (alkohol or drugs)</li>
                    <li>Make the trainee show how to clock in, get a cab and advertise</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Dispatch Use and codes</h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <ul>
                    <li>How to use push to talk</li>
                    <li>How to deafen</li>
                    <li>Must talk in city when talking in discord (city rule, could lead to deportation if not done)</li>
                    <li>Code 0 - Game crash (Available soon again)</li>
                    <li>Code 1 - Ready to take a call (No local) (Undeafen)</li>
                    <li>Code 2 - With local customer (Can still take calls) (Undeafen)</li>
                    <li>Code 3 - With player customer (Deafen)</li>
                    <li>Code 4 - In danger (Robbery, kidnapped, being shot at) (Unavailable)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Answer Taxi calls and how to use phone</h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <ul>
                    <li>How to take player calls (if needed ask someone else to test call)</li>
                    <li>Don't need phone, you get a company phone while on shift and lose it when you clock off</li>
                    <li>Always answer (on the way and tell them distance/time to the caller)</li>
                    <li>Busy (private event. unavailable due to PD, EMS, training)</li>
                    <li>Text again later (optional, give estimate time when you can take their call)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>What to do</h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <ul>
                    <li>How to avoid fare meter breaking (always be last one out and fare meter unpaused when people getting out, only fix if broken is to fly out and in)</li>
                    <li>Priority of calls (text, by the road and then locals)</li>
                    <li>How do locals work (best way to try and not scare them)</li>
                    <li>How to start a conversation and keep it going (if customer seems interested in talking)</li>
                    <li>Tips &amp; tricks for highway Use</li>
                    <li>If you got no calls, then drive around and look for locals</li>
                    <li>Use cruise control as much as possible</li>
                    <li>Use cruise control to control your speed downhill</li>
                    <li>Payment: Paychecks, locals pay when at destination not when scared off, players pay when the last one gets out</li>
                    <li>Not allowed to brandishing a weapon while clocked in (weapons on back)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Own vehicles to while clocked in</h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <ul>
                    <li>Taxi 33k, not allowed to put underglow on or tinted windows. Suggest to not waist money on tuning.</li>
                    <li>Limo 25k, not allowed to put underglow on.&nbsp;Suggest to not waist money on tuning.</li>
                    <li>Dont park own vehicles in the garage at cab co, they can poof when company cars are spawned</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <h5>Comments</h5>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <p>Have anything to say about this training session?</p>
            </div>
        </div>
        <form method="post" action="complete_day_one.php">
            <input name="id" value="<?= $student_id ?>" hidden>
            <input name="SubmitDocument" value="1" hidden>
            <div class="form-row">
                <div class="col d-flex justify-content-center"><textarea name="comments" class="form-control form-control-lg"></textarea></div>
            </div>
            <div class="form-row">
                <div class="col d-flex justify-content-center">
                    <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1" required=""><label class="form-check-label" for="formCheck-1">I confirm that this student has been fully informed of his role, and has demonstrated that he is able to conduct his duties.</label></div>
                </div>
            </div>
            <div class="form-row">
                <div class="col d-flex justify-content-center"><a class="btn btn-danger btn-pad" type="button" href="/training/day_one/table_day_one.php">Cancel</a><button class="btn btn-success btn-pad" type="submit">Submit</button></div>
            </div>
        </form>
    </div>
</section>
<?php
include "../../include/components/foot.php";
