<?php
// admission-system/dashboard/support_register.php
// Register New Student Component
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">

<form method="POST" id="registrationForm" enctype="multipart/form-data">

    <!-- SECTION 1: PERSONAL DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-user"></i> 1. Personal Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Full Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="full_name" class="form-control" placeholder="Enter full name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Date of Birth <span style="color: #e74c3c;">*</span></label>
                    <input type="date" name="date_of_birth" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Gender <span style="color: #e74c3c;">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Nationality</label>
                    <input type="text" name="nationality" class="form-control" value="Indian" placeholder="Nationality">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Religion</label>
                    <input type="text" name="religion" class="form-control" placeholder="Enter religion">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Community/Category <span style="color: #e74c3c;">*</span></label>
                    <select name="community" class="form-select" required id="community_select">
                        <option value="">Select Category</option>
                        <option value="General">General</option>
                        <option value="OC">OC</option>
                        <option value="BC">BC</option>
                        <option value="BCM">BCM</option>
                        <option value="MBC/DNC">MBC/DNC</option>
                        <option value="SC">SC</option>
                        <option value="SCA">SCA</option>
                        <option value="ST">ST</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" name="community_other" id="community_other" class="form-control mt-2" placeholder="Please specify other category" style="display: none;">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Aadhaar Number</label>
                    <input type="text" name="aadhaar_number" class="form-control" placeholder="12-digit Aadhaar" pattern="[0-9]{12}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Blood Group</label>
                    <select name="blood_group" class="form-select">
                        <option value="">Select Blood Group</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">First Graduate</label>
                    <select name="first_graduate" class="form-select">
                        <option value="">Select</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6" id="first_graduate_certificate_container" style="display: none;">
                <div class="field-group">
                    <label class="field-label">First Graduate Certificate</label>
                    <input type="file" name="first_graduate_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Passport Photo</label>
                    <input type="file" name="passport_photo" class="form-control" accept="image/*">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: CONTACT DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-phone"></i> 2. Contact Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Mobile Number <span style="color: #e74c3c;">*</span></label>
                    <input type="tel" name="mobile_number" class="form-control" placeholder="10-digit mobile" pattern="[0-9]{10}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Alternate Mobile Number</label>
                    <input type="tel" name="alternate_mobile" class="form-control" placeholder="Alternate mobile" pattern="[0-9]{10}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Email ID <span style="color: #e74c3c;">*</span></label>
                    <input type="email" name="email_id" class="form-control" placeholder="your.email@example.com" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">City <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="city" class="form-control" placeholder="Enter city name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">State <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="state" class="form-control" placeholder="Enter state name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Pincode <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="pincode" class="form-control" placeholder="6-digit pincode" pattern="[0-9]{6}" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Permanent Address <span style="color: #e74c3c;">*</span></label>
                    <textarea name="permanent_address" class="form-control" rows="3" placeholder="Enter complete permanent address" required></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Current Address</label>
                    <textarea name="current_address" id="current_address" class="form-control" rows="3" placeholder="Enter complete current address"></textarea>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="same_address_checkbox">
                        <label class="form-check-label" for="same_address_checkbox" style="color: #495057;">
                            Same as Permanent Address
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: PARENT/GUARDIAN DETAILS -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-users"></i> 3. Parent / Guardian Details</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control" placeholder="Father's full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Mother's Name</label>
                    <input type="text" name="mother_name" class="form-control" placeholder="Mother's full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Guardian Name</label>
                    <input type="text" name="guardian_name" class="form-control" placeholder="Guardian's full name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Parent Occupation</label>
                    <input type="text" name="parent_occupation" class="form-control" placeholder="Parent's occupation">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Parent Mobile Number</label>
                    <input type="tel" name="parent_mobile" class="form-control" placeholder="Parent's mobile" pattern="[0-9]{10}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Parent Email ID</label>
                    <input type="email" name="parent_email" class="form-control" placeholder="Parent's email">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Annual Family Income</label>
                    <input type="number" name="annual_family_income" class="form-control" placeholder="Annual income in rupees">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Income Certificate</label>
                    <input type="file" name="income_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: ACADEMIC DETAILS (10TH) -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-book"></i> 4. Academic Details - 10th Class</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">School Name</label>
                    <input type="text" name="class_10_school" class="form-control" placeholder="10th school name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Board</label>
                    <select name="class_10_board" class="form-select">
                        <option value="State Board">State Board</option>
                        <option value="CBSE">CBSE</option>
                        <option value="ICSE">ICSE</option>
                        <option value="IB">IB</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Register Number</label>
                    <input type="text" name="class_10_register_number" class="form-control" placeholder="10th register number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Percentage / CGPA</label>
                    <input type="number" step="0.01" name="class_10_percentage" class="form-control" placeholder="0.00">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Marksheet</label>
                    <input type="file" name="class_10_marksheet" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 5: ACADEMIC DETAILS (12TH) -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-book"></i> 5. Academic Details - 12th Class</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">School Name</label>
                    <input type="text" name="class_12_school" class="form-control" placeholder="12th school name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Board</label>
                    <select name="class_12_board" class="form-select">
                        <option value="State Board">State Board</option>
                        <option value="CBSE">CBSE</option>
                        <option value="ICSE">ICSE</option>
                        <option value="IB">IB</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Register Number</label>
                    <input type="text" name="class_12_register_number" class="form-control" placeholder="12th register number">
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Cutoff Percentage (From 200 Marks)</label>
                    <input type="number" step="0.01" name="class_12_percentage" class="form-control" placeholder="0.00" readonly>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Enter 12th Subject Marks</label>
                    <p class="text-muted" style="font-size: 0.95rem;">Enter marks for Maths (100 marks), Physics (100 marks, converted to 50), and Chemistry (100 marks, converted to 50). Cutoff calculated out of 200 marks only for MPC subjects.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-group">
                    <label class="field-label">Mathematics Marks</label>
                    <input type="number" step="0.01" min="0" max="100" name="class_12_maths_marks" class="form-control class-12-mark-input" placeholder="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-group">
                    <label class="field-label">Physics Marks</label>
                    <input type="number" step="0.01" min="0" max="100" name="class_12_physics_marks" class="form-control class-12-mark-input" placeholder="0">
                </div>
            </div>
            <div class="col-md-4">
                <div class="field-group">
                    <label class="field-label">Chemistry Marks</label>
                    <input type="number" step="0.01" min="0" max="100" name="class_12_chemistry_marks" class="form-control class-12-mark-input" placeholder="0">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Cutoff Mark (Maths + Physics/2 + Chemistry/2) - Out of 200</label>
                    <input type="number" step="0.01" name="class_12_cutoff_mark" class="form-control" placeholder="0.00" readonly style="font-weight: bold; font-size: 1.1em; color: #2ecc71;">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Subjects Studied</label>
                    <select name="class_12_subjects" class="form-select" required>
                        <option value="">Select Subject Combination</option>
                        <option value="Mathematics, Physics, Chemistry" selected>Mathematics, Physics, Chemistry</option>
                        <option value="Mathematics, Biology">Math Biology</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Pure Biology">Pure Biology</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Marksheet</label>
                    <input type="file" name="class_12_marksheet" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Transfer Certificate</label>
                    <input type="file" name="transfer_certificate" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 6: PROGRAMME CHOICE -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-graduation-cap"></i> 6. Programme Choice</h3>

        <!-- Selection Counter -->
        <div class="text-center mb-4">
            <div class="counter-display">
                <i class="fas fa-check-circle"></i>
                <span id="selected-count">0</span> programmes selected
            </div>
        </div>

        <!-- Programme Categories -->
        <div class="programme-simple-list">

            <!-- Undergraduate Engineering -->
            <div class="programme-category">
                <h5 class="category-title"><i class="fas fa-cogs"></i> Undergraduate Engineering</h5>
                <div class="category-subtitle">4 Years | B.E/B.Tech Degree</div>
                <div class="programme-options">
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.E (Civil)" id="prog_1" class="programme-checkbox">
                        <label for="prog_1">B.E (Civil)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="BE (CSE)" id="prog_2" class="programme-checkbox">
                        <label for="prog_2">BE (CSE)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="BE (CSE-Cyb. Sec.)" id="prog_3" class="programme-checkbox">
                        <label for="prog_3">BE (CSE-Cyb. Sec.)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="BE (CSE-A & MI)" id="prog_4" class="programme-checkbox">
                        <label for="prog_4">BE (CSE-A & MI)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.E (EEE)" id="prog_5" class="programme-checkbox">
                        <label for="prog_5">B.E (EEE)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.E (EE-VLSI)" id="prog_6" class="programme-checkbox">
                        <label for="prog_6">B.E (EE-VLSI)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="BE (Mech)" id="prog_7" class="programme-checkbox">
                        <label for="prog_7">BE (Mech)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.E (Med. Elec.)" id="prog_8" class="programme-checkbox">
                        <label for="prog_8">B.E (Med. Elec.)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="BE (Rob. & Auto.)" id="prog_9" class="programme-checkbox">
                        <label for="prog_9">BE (Rob. & Auto.)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Tech (IT)" id="prog_10" class="programme-checkbox">
                        <label for="prog_10">B.Tech (IT)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Tech (Al & DS)" id="prog_11" class="programme-checkbox">
                        <label for="prog_11">B.Tech (Al & DS)</label>
                    </div>
                </div>
            </div>

            <!-- Postgraduate Engineering & MBA -->
            <div class="programme-category">
                <h5 class="category-title"><i class="fas fa-graduation-cap"></i> Postgraduate Engineering & MBA</h5>
                <div class="category-subtitle">2 Years | M.E/M.Tech/MBA Degree</div>
                <div class="programme-options">
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="M.E (CSE)" id="prog_12" class="programme-checkbox">
                        <label for="prog_12">M.E (CSE)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="M.E (Stru. Eng.)" id="prog_13" class="programme-checkbox">
                        <label for="prog_13">M.E (Stru. Eng.)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="M.E (VLSI)" id="prog_14" class="programme-checkbox">
                        <label for="prog_14">M.E (VLSI)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="M.E (MEE)" id="prog_15" class="programme-checkbox">
                        <label for="prog_15">M.E (MEE)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="RESEARCH(Ph.D)" id="prog_16" class="programme-checkbox">
                        <label for="prog_16">RESEARCH(Ph.D)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="MBA" id="prog_17" class="programme-checkbox">
                        <label for="prog_17">MBA</label>
                    </div>
                </div>
            </div>

            <!-- Pharmacy -->
            <div class="programme-category">
                <h5 class="category-title"><i class="fas fa-pills"></i> Pharmacy</h5>
                <div class="category-subtitle">2-6 Years | Pharmacy Degree</div>
                <div class="programme-options">
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="D.Pharm" id="prog_18" class="programme-checkbox">
                        <label for="prog_18">D.Pharm</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Pharm" id="prog_19" class="programme-checkbox">
                        <label for="prog_19">B.Pharm</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="M.Pharm (Pharmaceutics)" id="prog_20" class="programme-checkbox">
                        <label for="prog_20">M.Pharm (Pharmaceutics)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="M.Pharm (Reg. Aff)" id="prog_21" class="programme-checkbox">
                        <label for="prog_21">M.Pharm (Reg. Aff)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="Pharm. D." id="prog_22" class="programme-checkbox">
                        <label for="prog_22">Pharm. D.</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="Pharm. D. (Post Baccalaureate)" id="prog_23" class="programme-checkbox">
                        <label for="prog_23">Pharm. D. (Post Baccalaureate)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Tech (Pharm. Tech.)" id="prog_24" class="programme-checkbox">
                        <label for="prog_24">B.Tech (Pharm. Tech.)</label>
                    </div>
                </div>
            </div>

            <!-- Para Medical & Allied Health -->
            <div class="programme-category">
                <h5 class="category-title"><i class="fas fa-heartbeat"></i> Para Medical & Allied Health</h5>
                <div class="category-subtitle">3-4 Years | B.Sc Degree</div>
                <div class="programme-options">
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Sc Nursing" id="prog_25" class="programme-checkbox">
                        <label for="prog_25">B.Sc Nursing</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Sc (MLT)" id="prog_26" class="programme-checkbox">
                        <label for="prog_26">B.Sc (MLT)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Sc (Radiology & Imaging Technology)" id="prog_27" class="programme-checkbox">
                        <label for="prog_27">B.Sc (Radiology & Imaging Technology)</label>
                    </div>
                    <div class="programme-item">
                        <input type="checkbox" name="programme_choice[]" value="B.Sc (OT & AT)" id="prog_28" class="programme-checkbox">
                        <label for="prog_28">B.Sc (OT & AT)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 7: ADDITIONAL INFORMATION -->
    <div class="section-card">
        <h3 class="section-title"><i class="fas fa-info-circle"></i> 7. Additional Information</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Hostel Requirement</label>
                    <select name="hostel_requirement" class="form-select">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="field-group">
                    <label class="field-label">Transport Requirement</label>
                    <select name="transport_requirement" class="form-select">
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Scholarship Details</label>
                    <textarea name="scholarship_details" class="form-control" rows="2" placeholder="If any scholarship details"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Sports / Extracurricular Achievements</label>
                    <textarea name="sports_achievements" class="form-control" rows="2" placeholder="List achievements if any"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="field-group">
                    <label class="field-label">Medical Information</label>
                    <textarea name="medical_information" class="form-control" rows="2" placeholder="Any medical conditions or allergies"></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="section-card" style="text-align: center; background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border-left-color: #2ecc71;">
        <button type="submit" name="register_student" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">
            <i class="fas fa-check-circle"></i> Register Student & Generate Admission ID
        </button>
        <p style="color: #999; margin-top: 15px; font-size: 0.9rem;">
            <i class="fas fa-info-circle"></i> All fields marked with <span style="color: #e74c3c;">*</span> are mandatory
        </p>
    </div>
</form>

<!-- FORM VALIDATION SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
<script>
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const fullName = document.querySelector('input[name="full_name"]').value.trim();
        const email = document.querySelector('input[name="email_id"]').value.trim();
        const mobile = document.querySelector('input[name="mobile_number"]').value.trim();
        const dob = document.querySelector('input[name="date_of_birth"]').value;
        const degreeType = document.querySelector('select[name="degree_type"]').value;
        const courseDept = document.querySelector('input[name="course_department"]').value.trim();

        if (!fullName || !email || !mobile || !dob || !degreeType || !courseDept) {
            e.preventDefault();
            alert('Please fill all required fields');
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address');
            return false;
        }

        // Mobile validation
        if (!/^\d{10}$/.test(mobile)) {
            e.preventDefault();
            alert('Please enter a valid 10-digit mobile number');
            return false;
        }
    });

    // Address copy functionality
    document.getElementById('same_address_checkbox').addEventListener('change', function() {
        const permanentAddress = document.querySelector('textarea[name="permanent_address"]');
        const currentAddress = document.getElementById('current_address');

        if (this.checked) {
            // Copy permanent address to current address
            currentAddress.value = permanentAddress.value;
        } else {
            // Clear current address when unchecked
            currentAddress.value = '';
        }
    });

    // Also update current address when permanent address changes and checkbox is checked
    document.querySelector('textarea[name="permanent_address"]').addEventListener('input', function() {
        const checkbox = document.getElementById('same_address_checkbox');
        const currentAddress = document.getElementById('current_address');

        if (checkbox.checked) {
            currentAddress.value = this.value;
        }
    });

    // First Graduate certificate toggle
    document.querySelector('select[name="first_graduate"]').addEventListener('change', function() {
        const container = document.getElementById('first_graduate_certificate_container');
        if (this.value === 'Yes') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
            document.querySelector('input[name="first_graduate_certificate"]').value = '';
        }
    });

    // Community Other field toggle
    document.getElementById('community_select').addEventListener('change', function() {
        const otherInput = document.getElementById('community_other');
        if (this.value === 'Other') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            otherInput.value = '';
        }
    });
</script>
