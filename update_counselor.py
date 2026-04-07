import re

file_path = 'c:/xampp/htdocs/Admission-Project/dashboard/counselor_dashboard.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update the POST Variables Extraction
post_vars_extra = """
    $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $first_graduate = $conn->real_escape_string($_POST['first_graduate'] ?? '');
    $alternate_mobile = $conn->real_escape_string($_POST['alternate_mobile'] ?? '');
    $current_address = $conn->real_escape_string($_POST['current_address'] ?? '');
    $father_name = $conn->real_escape_string($_POST['father_name'] ?? '');
    $mother_name = $conn->real_escape_string($_POST['mother_name'] ?? '');
    $guardian_name = $conn->real_escape_string($_POST['guardian_name'] ?? '');
    $parent_occupation = $conn->real_escape_string($_POST['parent_occupation'] ?? '');
    $parent_mobile = $conn->real_escape_string($_POST['parent_mobile'] ?? '');
    $parent_email = $conn->real_escape_string($_POST['parent_email'] ?? '');
    $annual_family_income = isset($_POST['annual_family_income']) ? (int)$_POST['annual_family_income'] : 0;
    
    $class_10_school = $conn->real_escape_string($_POST['class_10_school'] ?? '');
    $class_10_board = $conn->real_escape_string($_POST['class_10_board'] ?? '');
    $class_10_register_number = $conn->real_escape_string($_POST['class_10_register_number'] ?? '');
    $class_10_percentage = isset($_POST['class_10_percentage']) ? (float)$_POST['class_10_percentage'] : 0;
    
    $class_12_register_number = $conn->real_escape_string($_POST['class_12_register_number'] ?? '');
    $class_12_subject_1_marks = isset($_POST['class_12_subject_1_marks']) && $_POST['class_12_subject_1_marks'] !== '' ? (float)$_POST['class_12_subject_1_marks'] : 'NULL';
    $class_12_subject_2_marks = isset($_POST['class_12_subject_2_marks']) && $_POST['class_12_subject_2_marks'] !== '' ? (float)$_POST['class_12_subject_2_marks'] : 'NULL';
    $class_12_subject_3_marks = isset($_POST['class_12_subject_3_marks']) && $_POST['class_12_subject_3_marks'] !== '' ? (float)$_POST['class_12_subject_3_marks'] : 'NULL';
    $class_12_subject_4_marks = isset($_POST['class_12_subject_4_marks']) && $_POST['class_12_subject_4_marks'] !== '' ? (float)$_POST['class_12_subject_4_marks'] : 'NULL';
    $class_12_subject_5_marks = isset($_POST['class_12_subject_5_marks']) && $_POST['class_12_subject_5_marks'] !== '' ? (float)$_POST['class_12_subject_5_marks'] : 'NULL';
    $class_12_subjects = $conn->real_escape_string($_POST['class_12_subjects'] ?? '');
    $programme_choice = $conn->real_escape_string($_POST['programme_choice'] ?? '');
    
    $hostel_requirement = $conn->real_escape_string($_POST['hostel_requirement'] ?? 'No');
    $transport_requirement = $conn->real_escape_string($_POST['transport_requirement'] ?? 'No');
    $scholarship_details = $conn->real_escape_string($_POST['scholarship_details'] ?? '');
    $sports_achievements = $conn->real_escape_string($_POST['sports_achievements'] ?? '');
    $medical_information = $conn->real_escape_string($_POST['medical_information'] ?? '');
"""

sql_updates_extra = """
        blood_group = '$blood_group',
        first_graduate = '$first_graduate',
        alternate_mobile = '$alternate_mobile',
        current_address = '$current_address',
        father_name = '$father_name',
        mother_name = '$mother_name',
        guardian_name = '$guardian_name',
        parent_occupation = '$parent_occupation',
        parent_mobile = '$parent_mobile',
        parent_email = '$parent_email',
        annual_family_income = $annual_family_income,
        class_10_school = '$class_10_school',
        class_10_board = '$class_10_board',
        class_10_register_number = '$class_10_register_number',
        class_10_percentage = $class_10_percentage,
        class_12_register_number = '$class_12_register_number',
        class_12_subject_1_marks = $class_12_subject_1_marks,
        class_12_subject_2_marks = $class_12_subject_2_marks,
        class_12_subject_3_marks = $class_12_subject_3_marks,
        class_12_subject_4_marks = $class_12_subject_4_marks,
        class_12_subject_5_marks = $class_12_subject_5_marks,
        class_12_subjects = '$class_12_subjects',
        programme_choice = '$programme_choice',
        hostel_requirement = '$hostel_requirement',
        transport_requirement = '$transport_requirement',
        scholarship_details = '$scholarship_details',
        sports_achievements = '$sports_achievements',
        medical_information = '$medical_information',
"""

content = content.replace("$application_status = $conn->real_escape_string($_POST['application_status']);", "$application_status = $conn->real_escape_string($_POST['application_status']);\n" + post_vars_extra)
content = content.replace("application_status = '$application_status'", sql_updates_extra + "\n        application_status = '$application_status'")


# 2. Update the HTML Form
# I will find the <!-- SECTION 3: ACADEMIC DETAILS --> block and insert the missing sections before moving to course selection, 
# and also I'll modify the Personal and Contact sections slightly to add missing fields.

html_personal_contact_extras = """
<!-- Personal Extras -->
<div class="col-md-6"><div class="mb-3"><label class="form-label">Blood Group</label><input type="text" name="blood_group" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['blood_group'] ?? ''); ?>"></div></div>
<div class="col-md-6"><div class="mb-3"><label class="form-label">First Graduate</label><select name="first_graduate" class="form-select"><option value="Yes" <?php echo ($selectedStudent['first_graduate']??'')=='Yes'?'selected':''; ?>>Yes</option><option value="No" <?php echo ($selectedStudent['first_graduate']??'')=='No'?'selected':''; ?>>No</option></select></div></div>
"""

content = content.replace('<!-- SECTION 2: CONTACT DETAILS -->', html_personal_contact_extras + '\n<!-- SECTION 2: CONTACT DETAILS -->')

html_contact_extras = """
<div class="col-md-6"><div class="mb-3"><label class="form-label">Alternate Mobile</label><input type="text" name="alternate_mobile" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['alternate_mobile'] ?? ''); ?>"></div></div>
<div class="col-md-12"><div class="mb-3"><label class="form-label">Current Address</label><textarea name="current_address" class="form-control" rows="3"><?php echo htmlspecialchars($selectedStudent['current_address'] ?? ''); ?></textarea></div></div>
"""

content = content.replace('<!-- SECTION 3: ACADEMIC DETAILS -->', html_contact_extras + """
<!-- PARENT DETAILS -->
<div class="section-card"><h3 class="section-title"><i class="fas fa-users"></i> Parent / Guardian Details</h3>
<div class="row">
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Father's Name</label><input type="text" name="father_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['father_name'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Mother's Name</label><input type="text" name="mother_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['mother_name'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Guardian Name</label><input type="text" name="guardian_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['guardian_name'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Parent Occupation</label><input type="text" name="parent_occupation" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['parent_occupation'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Parent Mobile</label><input type="text" name="parent_mobile" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['parent_mobile'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Parent Email</label><input type="email" name="parent_email" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['parent_email'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Annual Family Income</label><input type="number" name="annual_family_income" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['annual_family_income'] ?? '0'); ?>"></div></div>
</div></div>

<!-- 10th DETAILS -->
<div class="section-card"><h3 class="section-title"><i class="fas fa-book"></i> 10th Academic Details</h3>
<div class="row">
    <div class="col-md-6"><div class="mb-3"><label class="form-label">School Name</label><input type="text" name="class_10_school" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_school'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Board</label><input type="text" name="class_10_board" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_board'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Register Number</label><input type="text" name="class_10_register_number" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_register_number'] ?? ''); ?>"></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Percentage</label><input type="number" step="0.01" name="class_10_percentage" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_percentage'] ?? '0'); ?>"></div></div>
</div></div>

""" + '\n<!-- SECTION 3: ACADEMIC DETAILS -->')

# Update 12th details manually within SECTION 3
html_12_extras = """
    <div class="mb-3"><label class="form-label">Register Number</label><input type="text" name="class_12_register_number" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_register_number'] ?? ''); ?>"></div>
    <div class="mb-3"><label class="form-label">Subjects Studied</label><input type="text" name="class_12_subjects" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subjects'] ?? ''); ?>"></div>
    <div class="mb-3"><label class="form-label">Subject 1 Marks</label><input type="number" step="0.01" name="class_12_subject_1_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_1_marks'] ?? ''); ?>"></div>
    <div class="mb-3"><label class="form-label">Subject 2 Marks</label><input type="number" step="0.01" name="class_12_subject_2_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_2_marks'] ?? ''); ?>"></div>
    <div class="mb-3"><label class="form-label">Subject 3 Marks</label><input type="number" step="0.01" name="class_12_subject_3_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_3_marks'] ?? ''); ?>"></div>
    <div class="mb-3"><label class="form-label">Subject 4 Marks</label><input type="number" step="0.01" name="class_12_subject_4_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_4_marks'] ?? ''); ?>"></div>
    <div class="mb-3"><label class="form-label">Subject 5 Marks</label><input type="number" step="0.01" name="class_12_subject_5_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_5_marks'] ?? ''); ?>"></div>
"""

content = content.replace('<label class="form-label">Percentage</label>', html_12_extras + '\n<label class="form-label">Percentage</label>')

# Update Course Selection with Programme choice
html_course_extras = """
<div class="col-md-12"><div class="mb-3"><label class="form-label">Programme Choice (Comma Separated)</label><input type="text" name="programme_choice" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['programme_choice'] ?? ''); ?>"></div></div>
"""
content = content.replace('<label class="form-label">Specialization</label>', html_course_extras + '\n<label class="form-label">Specialization</label>')

# Add ADDITIONAL INFORMATION section before SAVE BUTTONS
html_additional_extras = """
<!-- SECTION 5: ADDITIONAL INFO -->
<div class="section-card"><h3 class="section-title"><i class="fas fa-info-circle"></i> Additional Information</h3>
<div class="row">
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Hostel Requirement</label><select name="hostel_requirement" class="form-select"><option value="No" <?php echo ($selectedStudent['hostel_requirement']??'')=='No'?'selected':''; ?>>No</option><option value="Yes" <?php echo ($selectedStudent['hostel_requirement']??'')=='Yes'?'selected':''; ?>>Yes</option></select></div></div>
    <div class="col-md-6"><div class="mb-3"><label class="form-label">Transport Requirement</label><select name="transport_requirement" class="form-select"><option value="No" <?php echo ($selectedStudent['transport_requirement']??'')=='No'?'selected':''; ?>>No</option><option value="Yes" <?php echo ($selectedStudent['transport_requirement']??'')=='Yes'?'selected':''; ?>>Yes</option></select></div></div>
    <div class="col-md-12"><div class="mb-3"><label class="form-label">Scholarship Details</label><textarea name="scholarship_details" class="form-control" rows="2"><?php echo htmlspecialchars($selectedStudent['scholarship_details'] ?? ''); ?></textarea></div></div>
    <div class="col-md-12"><div class="mb-3"><label class="form-label">Sports Achievements</label><textarea name="sports_achievements" class="form-control" rows="2"><?php echo htmlspecialchars($selectedStudent['sports_achievements'] ?? ''); ?></textarea></div></div>
    <div class="col-md-12"><div class="mb-3"><label class="form-label">Medical Information</label><textarea name="medical_information" class="form-control" rows="2"><?php echo htmlspecialchars($selectedStudent['medical_information'] ?? ''); ?></textarea></div></div>
</div></div>
"""
content = content.replace('<!-- SAVE BUTTONS -->', html_additional_extras + '\n<!-- SAVE BUTTONS -->')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Update complete")
