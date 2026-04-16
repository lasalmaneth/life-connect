<div id="test-results" class="content-section" style="display: none;">
                    <div class="content-header">
                        <h2>Test Results</h2>
                        <p>Upload and review lab reports submitted by your hospital. Donors can view these under their Test Results page.</p>
                    </div>
                    <div class="content-body">
                        <div class="action-section">
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="openTestResultModal()">Upload Test Result</button>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="table-header">
                                <h4>Uploaded Results</h4>
                            </div>
                            <div class="table-content">
                                <div class="table-row" style="font-weight: 600; background: var(--gray-bg-color);">
                                    <div class="table-cell">Donor ID</div>
                                    <div class="table-cell">Test Name</div>
                                    <div class="table-cell">Test Date</div>
                                    <div class="table-cell">Result</div>
                                    <div class="table-cell">Document</div>
                                </div>
                                <?php if (!empty($test_results)): foreach ($test_results as $tr): ?>
                                    <div class="table-row">
                                        <div class="table-cell" data-label="Donor ID"><?php echo htmlspecialchars($tr->donor_id ?? ''); ?></div>
                                        <div class="table-cell" data-label="Test Name"><?php echo htmlspecialchars($tr->test_name ?? ''); ?></div>
                                        <div class="table-cell" data-label="Test Date"><?php echo htmlspecialchars(!empty($tr->test_date) ? date('d/m/Y', strtotime($tr->test_date)) : ''); ?></div>
                                        <div class="table-cell" data-label="Result"><?php echo htmlspecialchars($tr->result_value ?? ''); ?></div>
                                        <div class="table-cell" data-label="Document">
                                            <?php if (!empty($tr->document_path)): ?>
                                                <?php
                                                    $doc = (string)$tr->document_path;
                                                    $root = (string)(ROOT ?? '');
                                                    $isAbs = (strpos($doc, 'http://') === 0 || strpos($doc, 'https://') === 0);
                                                    $isRooted = ($root !== '' && strpos($doc, $root) === 0);
                                                    $href = ($isAbs || $isRooted) ? $doc : ($root . '/' . ltrim($doc, '/'));
                                                ?>
                                                <a href="<?php echo htmlspecialchars($href); ?>" target="_blank" rel="noopener" class="btn btn-secondary btn-small">View</a>
                                            <?php else: ?>
                                                <span style="color:#6b7280; font-size:.9rem;">—</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="table-row">
                                        <div class="table-cell" style="grid-column:1/-1; text-align:center; padding:20px; color:#999;">No test results uploaded yet</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>