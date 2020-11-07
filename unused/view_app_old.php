<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/include/header.php";
include_once($path);
?>
<h1 class="h2">
  char_name's Application
</h1>

<div class="span12 applicant-data">
  <h5 class="mb-3">
    User has made previous applications and is <span class="text-danger">Banned</span> <span class="text-success">Not Banned</span> From DTCC.
  </h5>
  <h5 class="mb-3">
    Character Name:
    <span class="font-weight-normal">char_name</span>
  </h5>
  <h5 class="mb-3">
    Steam Name:
    <span class="font-weight-normal">steam_name</span>
  </h5>
  <h5 class="mb-3">
    Phone Number:
    <span class="font-weight-normal">phone_number</span>
  </h5>
  <h5 class="mb-3">
    Discord:
    <span class="font-weight-normal">discord_name</span>
  </h5>
  <h5 class="mb-3">
    Steam Profile:
    <a href="" target="_blank"><span class="font-weight-normal">steam_link</span></a>
  </h5>
  <h5 class="mb-4">
    Backstory:<br /><span class="font-weight-normal">char_backstory</span>
  </h5>
  <h5 class="mb-4">
    Why do you want to join Downtown Cab Co?<br /><span class="font-weight-normal">char_reason</span>
  </h5>
  <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#acceptModal">
    Accept
  </button>
  <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#denyModal">
    Deny
  </button>
  <a type="button" class="btn btn-secondary float-right" data-dismiss="modal" href="">Back</a>
</div>
<!-- Modals -->
<!-- Accept Applicant Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="acceptModalLabel">
          Accept Applicant Confirmation
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="text-center mb-3">
          Are you sure you want to accept the applicant?
        </h5>
        <h5 class="text-center mb-3">
          Please enter the SteamID64 of this user to continue.
        </h5>
        <h5 class="text-center mb-4">
          Steam Profile Link:
          <span><a href="" target="_blank"><span class="font-weight-normal">steam_link</span></a></span>
        </h5>
        <div class="input-group steam-input">
          <div class="input-group-prepend">
            <span class="input-group-text">
              <h5 class="mb-0">Steam</h5>
            </span>
          </div>
          <input id="hex" type="text" class="form-control" value="" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="SubmitApp('accept')" data-dismiss="modal">
          Accept Applicant
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Back
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Deny Applicant Modal -->
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="denyModalLabel">
          Deny Applicant Confirmation
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <h5 class="text-center mb-3">
                Are you sure you want to reject this applicant?
              </h5>
            </div>
            <form class="row mx-0 w-100">
              <div class="col-md-6 mt-2">
                <h5>Reasons for denial</h5>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="banned" />
                  <label class="custom-control-label" for="banned">
                    <h6 class="font-weight-normal">
                      User is Banned from DTCC
                    </h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badCharacterName" />
                  <label class="custom-control-label" for="badCharacterName">
                    <h6 class="font-weight-normal">Bad Character Name</h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badSteamName" />
                  <label class="custom-control-label" for="badSteamName">
                    <h6 class="font-weight-normal">Bad Steam Name</h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badPhoneNumber" />
                  <label class="custom-control-label" for="badPhoneNumber">
                    <h6 class="font-weight-normal">Bad Phone Number</h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badDiscordName" />
                  <label class="custom-control-label" for="badDiscordName">
                    <h6 class="font-weight-normal">Bad Discord Username</h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badProfileLink" value="" data-toggle="collapse" data-target="#badProfileLinkSwitch" aria-expanded="false" aria-controls="badProfileLinkSwitch" />
                  <label class="custom-control-label" for="badProfileLink">
                    <h6 class="font-weight-normal">Bad Profile Link</h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badBackstory" />
                  <label class="custom-control-label" for="badBackstory">
                    <h6 class="font-weight-normal">Bad Backstory</h6>
                  </label>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="badReason" />
                  <label class="custom-control-label" for="badReason">
                    <h6 class="font-weight-normal">Bad Reason</h6>
                  </label>
                </div>
                <div class="collapse show" id="badProfileLinkSwitch">
                  <p class="mt-2 mb-0">
                    <strong>Please enter the SteamID64 of this user to
                      continue:</strong>
                  </p>
                  <div class="input-group text-center">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <h5 class="mb-0">Steam</h5>
                      </span>
                    </div>
                    <input id="hex2" type="text" class="form-control" value="" />
                  </div>
                </div>
              </div>
              <div class="col-md-6 mt-2">
                <div class="form-group">
                  <textarea placeholder="Additional Information" class="form-control" id="additionalInformation" rows="5"></textarea>
                </div>
                <div class="custom-control custom-switch">
                  <input class="custom-control-input" type="checkbox" id="reapplyDays" data-toggle="collapse" data-target="#reapplyDaysSwitch" aria-expanded="false" aria-controls="reapplyDaysSwitch" />
                  <label class="custom-control-label" for="reapplyDays">
                    <h6 class="font-weight-normal" type="button">Reapply?</h6>
                  </label>
                </div>
                <div class="collapse" id="reapplyDaysSwitch">
                  <div class="input-group my-2 w-75">
                    <input type="text" class="form-control" placeholder="How many days?" aria-label="Days" aria-describedby="basic-addon2" id="reapplyDaysAmount" value="0" />
                    <div class="input-group-append">
                      <span class="input-group-text" id="basic-addon2">Days</span>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="SubmitApp('deny')" data-dismiss="modal">
          Reject Applicant
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Back
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Page Content Javascript -->
</div>
<?php
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/include/footer.php";
include_once($path);
?>