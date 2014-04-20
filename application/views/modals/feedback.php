<!-- Modal for writing the feedback -->
<div class="modal fade" id="write-feedback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title" id="myModalLabel">
                    Contact us
                </h2>
            </div>
            <div class="modal-body text-center">
                <p>
                    <input class="form-control" name="feedback-email" value="<?= $this->session->userdata('email'); ?>" type="text" placeholder="Your email address (optional)">
                </p>
                <p>
                    <textarea class="form-control" name="feedback-message" rows="8" placeholder="ʕʘ‿ʘʔ What happened?"></textarea>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-link" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-success" name="send-feedback">
                    <i class="fa fa-envelope"></i> Send
                </button>
            </div>
        </div>
    </div>
</div>   