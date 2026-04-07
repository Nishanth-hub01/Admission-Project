<?php
// admission-system/dashboard/support_new_view_student.php
// View Complete Student Details
?>

<!-- ACTION BUTTONS -->
<div style="margin-bottom: 25px; display: flex; gap: 10px;">
    <a href="?tab=all_students" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
    <button onclick="window.print()" class="btn btn-info">
        <i class="fas fa-print"></i> Print Details
    </button>
    <?php if (!empty($selectedStudent['admission_id'])): ?>
        <button onclick="copyAdmissionID('<?php echo $selectedStudent['admission_id']; ?>')" class="btn btn-success">
            <i class="fas fa-copy"></i> Copy Admission ID
        </button>
    <?php endif; ?>
</div>

<!-- ADMISSION ID DISPLAY -->
<?php if (!empty($selectedStudent['admission_id'])): ?>
    <div class="admission-id-box">
        <h4 style="margin: 0 0 10px 0; font-size: 1rem;">
            <i class="fas fa-id-card"></i> ADMISSION ID
        </h4>
        <div class="admission-id-display"><?php echo htmlspecialchars($selectedStudent['admission_id']); ?></div>
        <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 0.9rem;">
            Registered on: <?php echo date('d M Y, H:i', strtotime($selectedStudent['created_at'])); ?>
        </p>
    </div>
<?php endif; ?>

<!-- SECTION 1: PERSONAL DETAILS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-user"></i> Personal Details</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Full Name</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['full_name']); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Date of Birth</label>
                <div class="field-value"><?php echo !empty($selectedStudent['date_of_birth']) ? date('d M Y', strtotime($selectedStudent['date_of_birth'])) : 'N/A'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Gender</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['gender'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Nationality</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['nationality'] ?? 'Indian'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Religion</label>
                <div class="field-value"><?php echo !empty($selectedStudent['religion']) ? htmlspecialchars($selectedStudent['religion']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Community/Category</label>
                <div class="field-value">
                    <?php 
                    echo htmlspecialchars($selectedStudent['community'] ?? 'General');
                    if (!empty($selectedStudent['community_other'])) {
                        echo ' (' . htmlspecialchars($selectedStudent['community_other']) . ')';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Aadhaar Number</label>
                <div class="field-value"><?php echo !empty($selectedStudent['aadhaar_number']) ? htmlspecialchars($selectedStudent['aadhaar_number']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Blood Group</label>
                <div class="field-value"><?php echo !empty($selectedStudent['blood_group']) ? htmlspecialchars($selectedStudent['blood_group']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">First Graduate</label>
                <div class="field-value"><?php echo !empty($selectedStudent['first_graduate']) ? htmlspecialchars($selectedStudent['first_graduate']) : 'Not Provided'; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: CONTACT DETAILS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-phone"></i> Contact Details</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Mobile Number</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['mobile_number']); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Alternate Mobile</label>
                <div class="field-value"><?php echo !empty($selectedStudent['alternate_mobile']) ? htmlspecialchars($selectedStudent['alternate_mobile']) : 'N/A'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Email ID</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['email_id']); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">City</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['city'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">State</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['state'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Pincode</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['pincode'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Permanent Address</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['permanent_address'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Current Address</label>
                <div class="field-value"><?php echo !empty($selectedStudent['current_address']) ? htmlspecialchars($selectedStudent['current_address']) : 'Same as Permanent'; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 3: PARENT/GUARDIAN DETAILS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-users"></i> Parent / Guardian Details</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Father's Name</label>
                <div class="field-value"><?php echo !empty($selectedStudent['father_name']) ? htmlspecialchars($selectedStudent['father_name']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Mother's Name</label>
                <div class="field-value"><?php echo !empty($selectedStudent['mother_name']) ? htmlspecialchars($selectedStudent['mother_name']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Guardian Name</label>
                <div class="field-value"><?php echo !empty($selectedStudent['guardian_name']) ? htmlspecialchars($selectedStudent['guardian_name']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Parent Occupation</label>
                <div class="field-value"><?php echo !empty($selectedStudent['parent_occupation']) ? htmlspecialchars($selectedStudent['parent_occupation']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Parent Mobile Number</label>
                <div class="field-value"><?php echo !empty($selectedStudent['parent_mobile']) ? htmlspecialchars($selectedStudent['parent_mobile']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Parent Email ID</label>
                <div class="field-value"><?php echo !empty($selectedStudent['parent_email']) ? htmlspecialchars($selectedStudent['parent_email']) : 'Not Provided'; ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Annual Family Income</label>
                <div class="field-value">₹ <?php echo !empty($selectedStudent['annual_family_income']) ? number_format($selectedStudent['annual_family_income']) : '0'; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 4: ACADEMIC DETAILS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-book"></i> Academic Details</h3>
    <div class="row">
        <!-- 10th Class -->
        <div class="col-md-6">
            <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">📚 10th Class</h5>
            <div class="field-group">
                <label class="field-label">School Name</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_10_school'] ?? 'N/A'); ?></div>
            </div>
            <div class="field-group">
                <label class="field-label">Board</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_10_board'] ?? 'State Board'); ?></div>
            </div>
            <div class="field-group">
                <label class="field-label">Register Number</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_10_register_number'] ?? 'N/A'); ?></div>
            </div>
            <div class="field-group">
                <label class="field-label">Percentage / CGPA</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_10_percentage'] ?? 'N/A'); ?>%</div>
            </div>
        </div>

        <!-- 12th Class -->
        <div class="col-md-6">
            <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">📚 12th Class</h5>
            <div class="field-group">
                <label class="field-label">School Name</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_school'] ?? 'N/A'); ?></div>
            </div>
            <div class="field-group">
                <label class="field-label">Board</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_board'] ?? 'State Board'); ?></div>
            </div>
            <div class="field-group">
                <label class="field-label">Register Number</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_register_number'] ?? 'N/A'); ?></div>
            </div>
            <div class="field-group">
                <label class="field-label">Percentage</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_percentage'] ?? 'N/A'); ?>%</div>
            </div>
            <div class="field-group">
                <label class="field-label">Subject Marks</label>
                <div class="field-value">
                    <?php
                        $marks = [];
                        for ($i = 1; $i <= 5; $i++) {
                            $markKey = 'class_12_subject_' . $i . '_marks';
                            if (!empty($selectedStudent[$markKey])) {
                                $marks[] = 'Subject ' . $i . ': ' . htmlspecialchars($selectedStudent[$markKey]) . '%';
                            }
                        }
                        echo !empty($marks) ? implode(', ', $marks) : 'N/A';
                    ?>
                </div>
            </div>
        </div>

        <!-- Entrance Exam -->
        <div class="col-md-12" style="margin-top: 20px;">
            <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">🎯 Entrance Exam</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-group">
                        <label class="field-label">Exam Type</label>
                        <div class="field-value"><?php echo htmlspecialchars($selectedStudent['entrance_exam_type'] ?? 'N/A'); ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-group">
                        <label class="field-label">Score</label>
                        <div class="field-value"><?php echo htmlspecialchars($selectedStudent['entrance_exam_score'] ?? 'N/A'); ?></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="field-group">
                        <label class="field-label">Subjects Studied</label>
                        <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_subjects'] ?? 'N/A'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 5: COURSE SELECTION -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-graduation-cap"></i> Course Selection</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Degree Type</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['degree_type'] ?? 'B.Tech'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Department</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['course_department'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Programme Choice</label>
                <div class="field-value"><?php echo !empty($selectedStudent['programme_choice']) ? htmlspecialchars($selectedStudent['programme_choice']) : 'N/A'; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Preferred Specialization</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['preferred_specialization'] ?? 'N/A'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Admission Type</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['admission_type'] ?? 'Merit'); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 6: DOCUMENTS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-file-medical"></i> Documents</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="field-group">
                <label class="field-label">Passport Photo</label>
                <div class="field-value">
                    <?php if (!empty($selectedStudent['passport_photo'])): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($selectedStudent['passport_photo']); ?>" target="_blank">View Photo</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="field-group">
                <label class="field-label">10th Marksheet</label>
                <div class="field-value">
                    <?php if (!empty($selectedStudent['class_10_marksheet'])): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($selectedStudent['class_10_marksheet']); ?>" target="_blank">View Marksheet</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="field-group">
                <label class="field-label">12th Marksheet</label>
                <div class="field-value">
                    <?php if (!empty($selectedStudent['class_12_marksheet'])): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($selectedStudent['class_12_marksheet']); ?>" target="_blank">View Marksheet</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="field-group">
                <label class="field-label">First Graduate Certificate</label>
                <div class="field-value">
                    <?php if (!empty($selectedStudent['first_graduate_certificate'])): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($selectedStudent['first_graduate_certificate']); ?>" target="_blank">View Certificate</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="field-group">
                <label class="field-label">Income Certificate</label>
                <div class="field-value">
                    <?php if (!empty($selectedStudent['income_certificate'])): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($selectedStudent['income_certificate']); ?>" target="_blank">View Certificate</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="field-group">
                <label class="field-label">Transfer Certificate</label>
                <div class="field-value">
                    <?php if (!empty($selectedStudent['transfer_certificate'])): ?>
                        <a href="../uploads/<?php echo htmlspecialchars($selectedStudent['transfer_certificate']); ?>" target="_blank">View Certificate</a>
                    <?php else: ?>
                        Not Provided
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 7: BANK DETAILS -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-bank"></i> Bank Details</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Account Holder Name</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['account_holder_name'] ?? 'Not Provided'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Bank Name</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['bank_name'] ?? 'Not Provided'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Account Number</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['account_number'] ?? 'Not Provided'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">IFSC Code</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['ifsc_code'] ?? 'Not Provided'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Branch Name</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['branch_name'] ?? 'Not Provided'); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 7: ADDITIONAL INFORMATION -->
<div class="section-card">
    <h3 class="section-title"><i class="fas fa-info-circle"></i> Additional Information</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Hostel Requirement</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['hostel_requirement'] ?? 'No'); ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="field-group">
                <label class="field-label">Transport Requirement</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['transport_requirement'] ?? 'No'); ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Scholarship Details</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['scholarship_details'] ?? 'None'); ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Sports / Extracurricular Achievements</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['sports_achievements'] ?? 'None'); ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="field-group">
                <label class="field-label">Medical Information</label>
                <div class="field-value"><?php echo htmlspecialchars($selectedStudent['medical_information'] ?? 'None'); ?></div>
            </div>
        </div>
    </div>
</div>

<!-- APPLICATION STATUS -->
<div class="section-card" style="border-left-color: #2ecc71;">
    <h3 class="section-title" style="color: #2ecc71;"><i class="fas fa-check-circle"></i> Application Status</h3>
    <div style="padding: 20px; text-align: center; background: linear-gradient(135deg, rgba(46, 204, 113, 0.05) 0%, rgba(39, 174, 96, 0.05) 100%); border-radius: 8px;">
        <div style="font-size: 1.3rem; font-weight: bold; color: #2ecc71; margin-bottom: 10px;">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($selectedStudent['application_status']); ?>
        </div>
        <small style="color: #666;">
            Submitted on: <?php echo date('d M Y, H:i A', strtotime($selectedStudent['created_at'])); ?>
        </small>
    </div>
</div>