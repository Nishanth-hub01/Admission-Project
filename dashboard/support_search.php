<?php
// Search by Admission ID
?>
<div class="search-box">
    <h3 class="section-title" style="border: none; padding-bottom: 0;">
        <i class="fas fa-id-card"></i> Search Student by Admission ID
    </h3>
    
    <form method="POST" style="margin-top: 20px;">
        <div class="row">
            <div class="col-md-8">
                <label class="form-label" style="font-weight: 600;">Admission ID</label>
                <input type="text" name="admission_id" class="form-control" 
                       placeholder="Enter Admission ID (e.g., SI1003001)" 
                       pattern="^SI\d{7}$" 
                       style="font-family: 'Courier New', monospace; text-transform: uppercase; letter-spacing: 1px;"
                       required>
                <small style="color: #999; display: block; margin-top: 8px;">
                    Format: SI + Day + Month + Serial Number
                </small>
            </div>
            <div class="col-md-4" style="display: flex; align-items: flex-end;">
                <button type="submit" name="search_admission" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
    </form>
</div>

<?php if ($selectedStudent && $currentTab == 'search'): ?>
    <!-- SEARCH RESULT -->
    <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 1;">
                <h4 style="color: #333; font-weight: 700; margin-bottom: 15px;">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($selectedStudent['full_name']); ?>
                </h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <strong style="color: #667eea;">Admission ID</strong>
                        <p style="margin: 5px 0; color: #333; font-family: 'Courier New', monospace; font-size: 1.1rem;">
                            <?php echo $selectedStudent['admission_id']; ?>
                        </p>
                    </div>
                    <div>
                        <strong style="color: #667eea;">Mobile</strong>
                        <p style="margin: 5px 0; color: #333;"><?php echo htmlspecialchars($selectedStudent['mobile_number']); ?></p>
                    </div>
                    <div>
                        <strong style="color: #667eea;">Email</strong>
                        <p style="margin: 5px 0; color: #333;"><?php echo htmlspecialchars($selectedStudent['email_id']); ?></p>
                    </div>
                    <div>
                        <strong style="color: #667eea;">Department</strong>
                        <p style="margin: 5px 0; color: #333;"><?php echo htmlspecialchars($selectedStudent['course_department'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
            <div>
                <a href="?tab=view_student&view_student=<?php echo $selectedStudent['id']; ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> View Full Details
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>