<div class="modal" id="request-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Organ Request</h3>
                <button class="modal-close" onclick="closeRequestModal()">×</button>
            </div>
            <div>
                <input type="hidden" id="request-id" value="">
                <div class="form-group">
                    <label class="form-label">Organ Type</label>
                    <select class="form-select" id="organ-type">
                        <option value="">Select Organ</option>
                        <?php foreach (($organs ?? []) as $organ): ?>
                            <?php if (in_array($organ->name, ['Kidney', 'Part of Liver', 'Bone Marrow'])): ?>
                                <option value="<?= (int)$organ->id ?>"><?= htmlspecialchars($organ->name) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urgency Level</label>
                    <select class="form-select" id="urgency-level">
                        <option value="">Select Urgency</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Age</label>
                    <input class="form-input" id="recipient-age" type="number" min="18" max="80" placeholder="18 - 80">
                    <small id="age-error" style="color: #dc2626; display: none; margin-top: 0.25rem;"></small>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Blood Group</label>
                    <select class="form-select" id="recipient-blood-group">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Recipient Gender</label>
                    <select class="form-select" id="recipient-gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">HLA-typing</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;">
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-A (A1)</div>
                            <select class="form-select" id="recipient-hla-a1">
                                <option value="">Select Allele</option>
                                <option value="A*01">A*01</option>
                                <option value="A*02">A*02</option>
                                <option value="A*03">A*03</option>
                                <option value="A*11">A*11</option>
                                <option value="A*24">A*24</option>
                                <option value="A*33">A*33</option>
                                <option value="A*68">A*68</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-A (A2)</div>
                            <select class="form-select" id="recipient-hla-a2">
                                <option value="">Select Allele</option>
                                <option value="A*01">A*01</option>
                                <option value="A*02">A*02</option>
                                <option value="A*03">A*03</option>
                                <option value="A*11">A*11</option>
                                <option value="A*24">A*24</option>
                                <option value="A*33">A*33</option>
                                <option value="A*68">A*68</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-B (B1)</div>
                            <select class="form-select" id="recipient-hla-b1">
                                <option value="">Select Allele</option>
                                <option value="B*07">B*07</option>
                                <option value="B*08">B*08</option>
                                <option value="B*15">B*15</option>
                                <option value="B*35">B*35</option>
                                <option value="B*38">B*38</option>
                                <option value="B*44">B*44</option>
                                <option value="B*51">B*51</option>
                                <option value="B*52">B*52</option>
                                <option value="B*57">B*57</option>
                                <option value="B*58">B*58</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-B (B2)</div>
                            <select class="form-select" id="recipient-hla-b2">
                                <option value="">Select Allele</option>
                                <option value="B*07">B*07</option>
                                <option value="B*08">B*08</option>
                                <option value="B*15">B*15</option>
                                <option value="B*35">B*35</option>
                                <option value="B*38">B*38</option>
                                <option value="B*44">B*44</option>
                                <option value="B*51">B*51</option>
                                <option value="B*52">B*52</option>
                                <option value="B*57">B*57</option>
                                <option value="B*58">B*58</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-DRB1 (DR1)</div>
                            <select class="form-select" id="recipient-hla-dr1">
                                <option value="">Select Allele</option>
                                <option value="DRB1*01">DRB1*01</option>
                                <option value="DRB1*03">DRB1*03</option>
                                <option value="DRB1*04">DRB1*04</option>
                                <option value="DRB1*07">DRB1*07</option>
                                <option value="DRB1*11">DRB1*11</option>
                                <option value="DRB1*13">DRB1*13</option>
                                <option value="DRB1*14">DRB1*14</option>
                                <option value="DRB1*15">DRB1*15</option>
                            </select>
                        </div>
                        <div>
                            <div style="font-weight: 800; margin-bottom: .35rem;">HLA-DRB1 (DR2)</div>
                            <select class="form-select" id="recipient-hla-dr2">
                                <option value="">Select Allele</option>
                                <option value="DRB1*01">DRB1*01</option>
                                <option value="DRB1*03">DRB1*03</option>
                                <option value="DRB1*04">DRB1*04</option>
                                <option value="DRB1*07">DRB1*07</option>
                                <option value="DRB1*11">DRB1*11</option>
                                <option value="DRB1*13">DRB1*13</option>
                                <option value="DRB1*14">DRB1*14</option>
                                <option value="DRB1*15">DRB1*15</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="urgency-reason-group" class="form-group" style="display: none;">
                    <label class="form-label">Reason for Change <span style="color:red">*</span></label>
                    <textarea class="form-textarea" id="urgency-reason"
                        placeholder="Explain why the urgency was updated..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Transplant</label>
                    <textarea class="form-textarea" id="transplant-reason"
                        placeholder="e.g., End-stage renal disease"></textarea>
                </div>
                <button class="btn btn-primary" onclick="saveRequest()">Save Request</button>
            </div>
        </div>
    </div>