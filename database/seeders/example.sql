-- Clear existing data
TRUNCATE TABLE interactions;
TRUNCATE TABLE user_matches;
TRUNCATE TABLE photos;
TRUNCATE TABLE partner_preferences;
TRUNCATE TABLE profiles;
TRUNCATE TABLE subscriptions;
TRUNCATE TABLE password_reset_tokens;
TRUNCATE TABLE users;

-- Insert 50 male users
INSERT INTO users (id, name, email, email_verified_at, password, phone, gender, dob, religion, caste, sub_caste, marital_status, height, disability, mother_tongue, profile_created_by, verified, profile_completion, account_status, created_at, updated_at) VALUES
-- Hindu Brahmin males (10)
(1, 'Aarav Sharma', 'aarav1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'Male', '1990-05-15', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 175, 0, 'Hindi', 'Self', 1, 85, 'Active', NOW(), NOW()),
(2, 'Vihaan Patel', 'vihaan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543211', 'Male', '1988-07-20', 'Hindu', 'Brahmin', 'Gaur', 'Never Married', 168, 0, 'Hindi', 'Parent', 1, 75, 'Active', NOW(), NOW()),
(3, 'Aditya Joshi', 'aditya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543212', 'Male', '1985-11-10', 'Hindu', 'Brahmin', 'Sanadhya', 'Divorced', 182, 0, 'Hindi', 'Self', 1, 60, 'Active', NOW(), NOW()),
(4, 'Arjun Iyer', 'arjun1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543213', 'Male', '1992-03-25', 'Hindu', 'Brahmin', 'Iyer', 'Never Married', 170, 0, 'Tamil', 'Self', 1, 90, 'Active', NOW(), NOW()),
(5, 'Reyansh Nair', 'reyansh1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543214', 'Male', '1995-09-12', 'Hindu', 'Brahmin', 'Namboodiri', 'Never Married', 165, 0, 'Malayalam', 'Parent', 1, 80, 'Active', NOW(), NOW()),
(6, 'Atharva Deshpande', 'atharva1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543215', 'Male', '1993-12-05', 'Hindu', 'Brahmin', 'Deshastha', 'Never Married', 172, 0, 'Marathi', 'Self', 1, 70, 'Active', NOW(), NOW()),
(7, 'Dhruv Chaturvedi', 'dhruv1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543216', 'Male', '1987-08-18', 'Hindu', 'Brahmin', 'Kanyakubja', 'Awaiting Divorce', 178, 0, 'Hindi', 'Self', 1, 65, 'Active', NOW(), NOW()),
(8, 'Ishaan Mishra', 'ishaan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543217', 'Male', '1991-06-30', 'Hindu', 'Brahmin', 'Maithil', 'Never Married', 180, 0, 'Hindi', 'Parent', 1, 88, 'Active', NOW(), NOW()),
(9, 'Kabir Trivedi', 'kabir1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543218', 'Male', '1989-04-22', 'Hindu', 'Brahmin', 'Saraswat', 'Never Married', 169, 0, 'Konkani', 'Self', 1, 78, 'Active', NOW(), NOW()),
(10, 'Advait Tiwari', 'advait1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543219', 'Male', '1994-02-14', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 174, 0, 'Hindi', 'Self', 1, 82, 'Active', NOW(), NOW()),

-- Hindu Patel males (10)
(11, 'Rahul Patel', 'rahul1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543220', 'Male', '1988-11-15', 'Hindu', 'Patel', 'Leuva', 'Never Married', 171, 0, 'Gujarati', 'Parent', 1, 85, 'Active', NOW(), NOW()),
(12, 'Vivaan Patel', 'vivaan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543221', 'Male', '1990-07-22', 'Hindu', 'Patel', 'Kadva', 'Never Married', 176, 0, 'Gujarati', 'Self', 1, 75, 'Active', NOW(), NOW()),
-- Hindu Patel males (continued)
(13, 'Kunal Patel', 'kunal1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543222', 'Male', '1989-03-18', 'Hindu', 'Patel', 'Leuva', 'Never Married', 174, 0, 'Gujarati', 'Self', 1, 72, 'Active', NOW(), NOW()),
(14, 'Rohan Patel', 'rohan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543223', 'Male', '1991-12-11', 'Hindu', 'Patel', 'Kadva', 'Never Married', 169, 0, 'Gujarati', 'Parent', 1, 70, 'Active', NOW(), NOW()),
(15, 'Nirav Patel', 'nirav1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543224', 'Male', '1990-08-14', 'Hindu', 'Patel', 'Leuva', 'Divorced', 180, 0, 'Gujarati', 'Self', 1, 65, 'Active', NOW(), NOW()),
(16, 'Yash Patel', 'yash1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543225', 'Male', '1992-04-05', 'Hindu', 'Patel', 'Kadva', 'Never Married', 173, 0, 'Gujarati', 'Self', 1, 78, 'Active', NOW(), NOW()),
(17, 'Harsh Patel', 'harsh1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543226', 'Male', '1993-06-27', 'Hindu', 'Patel', 'Leuva', 'Never Married', 167, 0, 'Gujarati', 'Parent', 1, 69, 'Active', NOW(), NOW()),
(18, 'Manav Patel', 'manav1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543227', 'Male', '1987-10-30', 'Hindu', 'Patel', 'Kadva', 'Widowed', 175, 0, 'Gujarati', 'Self', 1, 74, 'Active', NOW(), NOW()),
(19, 'Parth Patel', 'parth1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543228', 'Male', '1991-11-21', 'Hindu', 'Patel', 'Leuva', 'Never Married', 172, 0, 'Gujarati', 'Self', 1, 77, 'Active', NOW(), NOW()),
(20, 'Jay Patel', 'jay1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543229', 'Male', '1994-09-13', 'Hindu', 'Patel', 'Kadva', 'Never Married', 168, 0, 'Gujarati', 'Parent', 1, 79, 'Active', NOW(), NOW()),


-- Sikh males (10)
(21, 'Gurpreet Singh', 'gurpreet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543230', 'Male', '1987-09-10', 'Sikh', 'Jat', NULL, 'Never Married', 179, 0, 'Punjabi', 'Self', 1, 90, 'Active', NOW(), NOW()),
-- Sikh males (continued)
(22, 'Harpreet Singh', 'harpreet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543231', 'Male', '1990-10-05', 'Sikh', 'Jat', NULL, 'Never Married', 181, 0, 'Punjabi', 'Parent', 1, 85, 'Active', NOW(), NOW()),
(23, 'Jagmeet Singh', 'jagmeet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543232', 'Male', '1988-02-19', 'Sikh', 'Jat', NULL, 'Divorced', 177, 0, 'Punjabi', 'Self', 1, 67, 'Active', NOW(), NOW()),
(24, 'Manjeet Singh', 'manjeet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543233', 'Male', '1989-07-23', 'Sikh', 'Jat', NULL, 'Never Married', 174, 0, 'Punjabi', 'Self', 1, 72, 'Active', NOW(), NOW()),
(25, 'Rajdeep Singh', 'rajdeep1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543234', 'Male', '1992-06-16', 'Sikh', 'Jat', NULL, 'Never Married', 176, 0, 'Punjabi', 'Parent', 1, 83, 'Active', NOW(), NOW()),
(26, 'Simranjit Singh', 'simranjit1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543235', 'Male', '1986-12-30', 'Sikh', 'Jat', NULL, 'Widowed', 178, 0, 'Punjabi', 'Self', 1, 69, 'Active', NOW(), NOW()),
(27, 'Sukhdeep Singh', 'sukhdeep1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543236', 'Male', '1990-01-25', 'Sikh', 'Jat', NULL, 'Never Married', 175, 0, 'Punjabi', 'Self', 1, 76, 'Active', NOW(), NOW()),
(28, 'Inderjeet Singh', 'inderjeet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543237', 'Male', '1988-05-11', 'Sikh', 'Jat', NULL, 'Awaiting Divorce', 173, 0, 'Punjabi', 'Parent', 1, 68, 'Active', NOW(), NOW()),
(29, 'Baljeet Singh', 'baljeet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543238', 'Male', '1993-04-07', 'Sikh', 'Jat', NULL, 'Never Married', 179, 0, 'Punjabi', 'Self', 1, 80, 'Active', NOW(), NOW()),
(30, 'Tejinder Singh', 'tejinder1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543239', 'Male', '1991-09-29', 'Sikh', 'Jat', NULL, 'Never Married', 170, 0, 'Punjabi', 'Parent', 1, 74, 'Active', NOW(), NOW()),


-- Muslim males (10)
(31, 'Ayaan Khan', 'ayaan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543240', 'Male', '1989-12-05', 'Muslim', 'Pathan', NULL, 'Never Married', 173, 0, 'Urdu', 'Parent', 1, 80, 'Active', NOW(), NOW()),
-- Muslim males (continued)
(32, 'Imran Ali', 'imran1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543241', 'Male', '1990-04-18', 'Muslim', 'Sheikh', NULL, 'Never Married', 171, 0, 'Urdu', 'Self', 1, 75, 'Active', NOW(), NOW()),
(33, 'Faisal Ahmad', 'faisal1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543242', 'Male', '1987-06-10', 'Muslim', 'Syed', NULL, 'Never Married', 177, 0, 'Urdu', 'Parent', 1, 83, 'Active', NOW(), NOW()),
(34, 'Zaid Qureshi', 'zaid1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543243', 'Male', '1993-01-15', 'Muslim', 'Qureshi', NULL, 'Never Married', 172, 0, 'Urdu', 'Self', 1, 72, 'Active', NOW(), NOW()),
(35, 'Tariq Rahman', 'tariq1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543244', 'Male', '1988-09-09', 'Muslim', 'Ansari', NULL, 'Divorced', 170, 0, 'Urdu', 'Self', 1, 65, 'Active', NOW(), NOW()),
(36, 'Sameer Sheikh', 'sameer1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543245', 'Male', '1991-02-20', 'Muslim', 'Sheikh', NULL, 'Never Married', 176, 0, 'Urdu', 'Parent', 1, 77, 'Active', NOW(), NOW()),
(37, 'Salman Khan', 'salman1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543246', 'Male', '1986-05-01', 'Muslim', 'Khan', NULL, 'Never Married', 179, 0, 'Urdu', 'Self', 1, 70, 'Active', NOW(), NOW()),
(38, 'Nasir Ansari', 'nasir1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543247', 'Male', '1989-11-17', 'Muslim', 'Ansari', NULL, 'Never Married', 173, 0, 'Urdu', 'Self', 1, 79, 'Active', NOW(), NOW()),
(39, 'Yusuf Farooqi', 'yusuf1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543248', 'Male', '1992-08-14', 'Muslim', 'Farooqi', NULL, 'Widowed', 174, 0, 'Urdu', 'Parent', 1, 68, 'Active', NOW(), NOW()),
(40, 'Rizwan Sheikh', 'rizwan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543249', 'Male', '1990-12-01', 'Muslim', 'Sheikh', NULL, 'Never Married', 169, 0, 'Urdu', 'Self', 1, 74, 'Active', NOW(), NOW()),

-- Christian males (10)
(41, 'Ethan D Souza', 'ethan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543250', 'Male', '1991-03-18', 'Christian', 'Catholic', NULL, 'Never Married', 177, 0, 'English', 'Self', 1, 85, 'Active', NOW(), NOW()),

(42, 'Noah Fernandes', 'noah1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543251', 'Male', '1990-04-12', 'Christian', 'Protestant', NULL, 'Never Married', 174, 0, 'English', 'Self', 1, 83, 'Active', NOW(), NOW()),
(43, 'Nathan D Costa', 'nathan1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543252', 'Male', '1988-11-07', 'Christian', 'Catholic', NULL, 'Divorced', 179, 0, 'English', 'Parent', 1, 70, 'Active', NOW(), NOW()),
(44, 'Liam Sequeira', 'liam1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543253', 'Male', '1986-08-15', 'Christian', 'Orthodox', NULL, 'Never Married', 182, 0, 'English', 'Self', 1, 78, 'Active', NOW(), NOW()),
(45, 'Aiden D Silva', 'aiden1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543254', 'Male', '1989-10-20', 'Christian', 'Catholic', NULL, 'Never Married', 170, 0, 'English', 'Self', 1, 76, 'Active', NOW(), NOW()),
(46, 'Caleb Pinto', 'caleb1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543255', 'Male', '1993-06-18', 'Christian', 'Protestant', NULL, 'Never Married', 176, 0, 'English', 'Parent', 1, 88, 'Active', NOW(), NOW()),
(47, 'Isaac Dias', 'isaac1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543256', 'Male', '1992-02-28', 'Christian', 'Catholic', NULL, 'Never Married', 173, 0, 'English', 'Self', 1, 81, 'Active', NOW(), NOW()),
(48, 'Elijah Gomes', 'elijah1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543257', 'Male', '1987-12-01', 'Christian', 'Catholic', NULL, 'Awaiting Divorce', 180, 0, 'English', 'Self', 1, 74, 'Active', NOW(), NOW()),
(49, 'Samuel Pereira', 'samuel1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543258', 'Male', '1995-03-10', 'Christian', 'Protestant', NULL, 'Never Married', 168, 0, 'English', 'Parent', 1, 86, 'Active', NOW(), NOW()),
(50, 'Daniel Coutinho', 'daniel1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543259', 'Male', '1991-01-19', 'Christian', 'Orthodox', NULL, 'Never Married', 175, 0, 'English', 'Self', 1, 79, 'Active', NOW(), NOW()),


-- Insert 50 female users
(51, 'Ananya Sharma', 'ananya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543260', 'Female', '1992-05-15', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 160, 0, 'Hindi', 'Self', 1, 88, 'Active', NOW(), NOW()),
-- Hindu Brahmin females (10)
(52, 'Aditi Sharma', 'aditi1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543261', 'Female', '1993-03-12', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 155, 0, 'Hindi', 'Self', 1, 85, 'Active', NOW(), NOW()),
(53, 'Ishita Joshi', 'ishita1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543262', 'Female', '1990-07-25', 'Hindu', 'Brahmin', 'Sanadhya', 'Never Married', 160, 0, 'Hindi', 'Parent', 1, 80, 'Active', NOW(), NOW()),
(54, 'Kavya Iyer', 'kavya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543263', 'Female', '1995-11-18', 'Hindu', 'Brahmin', 'Iyer', 'Never Married', 158, 0, 'Tamil', 'Self', 1, 90, 'Active', NOW(), NOW()),
(55, 'Meera Nair', 'meera1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543264', 'Female', '1992-06-10', 'Hindu', 'Brahmin', 'Namboodiri', 'Never Married', 162, 0, 'Malayalam', 'Parent', 1, 88, 'Active', NOW(), NOW()),
(56, 'Riya Deshpande', 'riya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543265', 'Female', '1994-09-05', 'Hindu', 'Brahmin', 'Deshastha', 'Never Married', 157, 0, 'Marathi', 'Self', 1, 75, 'Active', NOW(), NOW()),
(57, 'Sanya Chaturvedi', 'sanya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543266', 'Female', '1991-12-22', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 159, 0, 'Hindi', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(58, 'Tanya Mishra', 'tanya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543267', 'Female', '1990-04-15', 'Hindu', 'Brahmin', 'Maithil', 'Never Married', 161, 0, 'Hindi', 'Self', 1, 78, 'Active', NOW(), NOW()),
(59, 'Vaishnavi Trivedi', 'vaishnavi1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543268', 'Female', '1993-08-30', 'Hindu', 'Brahmin', 'Saraswat', 'Never Married', 156, 0, 'Konkani', 'Parent', 1, 85, 'Active', NOW(), NOW()),
(60, 'Yamini Tiwari', 'yamini1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543269', 'Female', '1991-02-14', 'Hindu', 'Brahmin', 'Kanyakubja', 'Never Married', 154, 0, 'Hindi', 'Self', 1, 80, 'Active', NOW(), NOW()),
(61, 'Zoya Sharma', 'zoya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543270', 'Female', '1994-05-20', 'Hindu', 'Brahmin', 'Gaur', 'Never Married', 160, 0, 'Hindi', 'Parent', 1, 88, 'Active', NOW(), NOW()),

-- Hindu Patel females (10)
(62, 'Riya Patel', 'riya2@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543271', 'Female', '1992-03-15', 'Hindu', 'Patel', 'Leuva', 'Never Married', 158, 0, 'Gujarati', 'Self', 1, 85, 'Active', NOW(), NOW()),
(63, 'Nidhi Patel', 'nidhi1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543272', 'Female', '1993-07-20', 'Hindu', 'Patel', 'Kadva', 'Never Married', 160, 0, 'Gujarati', 'Parent', 1, 80, 'Active', NOW(), NOW()),
(64, 'Krisha Patel', 'krisha1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543273', 'Female', '1990-05-10', 'Hindu', 'Patel', 'Leuva', 'Never Married', 157, 0, 'Gujarati', 'Self', 1, 78, 'Active', NOW(), NOW()),
(65, 'Mahi Patel', 'mahi1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543274', 'Female', '1991-11-25', 'Hindu', 'Patel', 'Kadva', 'Never Married', 159, 0, 'Gujarati', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(66, 'Jiya Patel', 'jiya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543275', 'Female', '1994-08-14', 'Hindu', 'Patel', 'Leuva', 'Never Married', 156, 0, 'Gujarati', 'Self', 1, 75, 'Active', NOW(), NOW()),
(67, 'Anika Patel', 'anika1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543276', 'Female', '1993-02-18', 'Hindu', 'Patel', 'Kadva', 'Never Married', 158, 0, 'Gujarati', 'Self', 1, 88, 'Active', NOW(), NOW()),
(68, 'Diya Patel', 'diya1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543277', 'Female', '1990-09-30', 'Hindu', 'Patel', 'Leuva', 'Never Married', 160, 0, 'Gujarati', 'Parent', 1, 80, 'Active', NOW(), NOW()),
(69, 'Isha Patel', 'isha1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543278', 'Female', '1992-06-12', 'Hindu', 'Patel', 'Kadva', 'Never Married', 157, 0, 'Gujarati', 'Self', 1, 78, 'Active', NOW(), NOW()),
(70, 'Kavya Patel', 'kavya2@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543279', 'Female', '1991-04-22', 'Hindu', 'Patel', 'Leuva', 'Never Married', 159, 0, 'Gujarati', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(71, 'Tanya Patel', 'tanya2@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543280', 'Female', '1994-12-05', 'Hindu', 'Patel', 'Kadva', 'Never Married', 156, 0, 'Gujarati', 'Self', 1, 85, 'Active', NOW(), NOW()),

-- Sikh females (10)
(72, 'Simran Kaur', 'simran1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543281', 'Female', '1992-01-15', 'Sikh', 'Jat', NULL, 'Never Married', 162, 0, 'Punjabi', 'Self', 1, 88, 'Active', NOW(), NOW()),
(73, 'Harleen Kaur', 'harleen1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543282', 'Female', '1993-05-20', 'Sikh', 'Jat', NULL, 'Never Married', 160, 0, 'Punjabi', 'Parent', 1, 85, 'Active', NOW(), NOW()),
(74, 'Jasleen Kaur', 'jasleen1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543283', 'Female', '1990-03-10', 'Sikh', 'Jat', NULL, 'Never Married', 158, 0, 'Punjabi', 'Self', 1, 80, 'Active', NOW(), NOW()),
(75, 'Navneet Kaur', 'navneet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543284', 'Female', '1991-09-25', 'Sikh', 'Jat', NULL, 'Never Married', 161, 0, 'Punjabi', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(76, 'Amrit Kaur', 'amrit1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543285', 'Female', '1994-07-14', 'Sikh', 'Jat', NULL, 'Never Married', 159, 0, 'Punjabi', 'Self', 1, 78, 'Active', NOW(), NOW()),
(77, 'Gurleen Kaur', 'gurleen1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543286', 'Female', '1993-02-18', 'Sikh', 'Jat', NULL, 'Never Married', 160, 0, 'Punjabi', 'Self', 1, 90, 'Active', NOW(), NOW()),
(78, 'Manpreet Kaur', 'manpreet1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543287', 'Female', '1990-09-30', 'Sikh', 'Jat', NULL, 'Never Married', 162, 0, 'Punjabi', 'Parent', 1, 85, 'Active', NOW(), NOW()),
(79, 'Rajveer Kaur', 'rajveer1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543288', 'Female', '1992-06-12', 'Sikh', 'Jat', NULL, 'Never Married', 158, 0, 'Punjabi', 'Self', 1, 80, 'Active', NOW(), NOW()),
(80, 'Taran Kaur', 'taran1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543289', 'Female', '1991-04-22', 'Sikh', 'Jat', NULL, 'Never Married', 161, 0, 'Punjabi', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(81, 'Kiran Kaur', 'kiran1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543290', 'Female', '1994-12-05', 'Sikh', 'Jat', NULL, 'Never Married', 159, 0, 'Punjabi', 'Self', 1, 88, 'Active', NOW(), NOW()),

-- Muslim females (10)
(82, 'Ayesha Khan', 'ayesha1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543291', 'Female', '1992-01-15', 'Muslim', 'Pathan', NULL, 'Never Married', 160, 0, 'Urdu', 'Self', 1, 85, 'Active', NOW(), NOW()),
(83, 'Zara Ali', 'zara2@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543292', 'Female', '1993-05-20', 'Muslim', 'Sheikh', NULL, 'Never Married', 158, 0, 'Urdu', 'Parent', 1, 80, 'Active', NOW(), NOW()),
(84, 'Fatima Ahmad', 'fatima1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543293', 'Female', '1990-03-10', 'Muslim', 'Syed', NULL, 'Never Married', 157, 0, 'Urdu', 'Self', 1, 78, 'Active', NOW(), NOW()),
(85, 'Sana Qureshi', 'sana1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543294', 'Female', '1991-09-25', 'Muslim', 'Qureshi', NULL, 'Never Married', 159, 0, 'Urdu', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(86, 'Hina Rahman', 'hina1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543295', 'Female', '1994-07-14', 'Muslim', 'Ansari', NULL, 'Never Married', 156, 0, 'Urdu', 'Self', 1, 75, 'Active', NOW(), NOW()),
(87, 'Sameera Sheikh', 'sameera1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543296', 'Female', '1993-02-18', 'Muslim', 'Sheikh', NULL, 'Never Married', 158, 0, 'Urdu', 'Self', 1, 88, 'Active', NOW(), NOW()),
(88, 'Nazia Khan', 'nazia1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543297', 'Female', '1990-09-30', 'Muslim', 'Khan', NULL, 'Never Married', 160, 0, 'Urdu', 'Parent', 1, 80, 'Active', NOW(), NOW()),
(89, 'Yasmin Ansari', 'yasmin1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543298', 'Female', '1992-06-12', 'Muslim', 'Ansari', NULL, 'Never Married', 157, 0, 'Urdu', 'Self', 1, 78, 'Active', NOW(), NOW()),
(90, 'Amina Farooqi', 'amina1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543299', 'Female', '1991-04-22', 'Muslim', 'Farooqi', NULL, 'Never Married', 159, 0, 'Urdu', 'Parent', 1, 82, 'Active', NOW(), NOW()),
(91, 'Rukhsar Sheikh', 'rukhsar1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543300', 'Female', '1994-12-05', 'Muslim', 'Sheikh', NULL, 'Never Married', 156, 0, 'Urdu', 'Self', 1, 85, 'Active', NOW(), NOW()),

-- Christian females (10)
(92, 'Sophia D Souza', 'sophia1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543301', 'Female', '1992-03-18', 'Christian', 'Catholic', NULL, 'Never Married', 165, 0, 'English', 'Self', 1, 85, 'Active', NOW(), NOW()),
(93, 'Olivia Fernandes', 'olivia1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543302', 'Female', '1991-04-12', 'Christian', 'Protestant', NULL, 'Never Married', 162, 0, 'English', 'Self', 1, 83, 'Active', NOW(), NOW()),
(94, 'Emma D Costa', 'emma1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543303', 'Female', '1990-11-07', 'Christian', 'Catholic', NULL, 'Divorced', 168, 0, 'English', 'Parent', 1, 70, 'Active', NOW(), NOW()),
(95, 'Ava Sequeira', 'ava1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543304', 'Female', '1989-08-15', 'Christian', 'Orthodox', NULL, 'Never Married', 170, 0, 'English', 'Self', 1, 78, 'Active', NOW(), NOW()),
(96, 'Mia D Silva', 'mia1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543305', 'Female', '1993-10-20', 'Christian', 'Catholic', NULL, 'Never Married', 160, 0, 'English', 'Self', 1, 76, 'Active', NOW(), NOW()),
(97, 'Isabella Pinto', 'isabella1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543306', 'Female', '1994-06-18', 'Christian', 'Protestant', NULL, 'Never Married', 164, 0, 'English', 'Parent', 1, 88, 'Active', NOW(), NOW()),
(98, 'Amelia Dias', 'amelia1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543307', 'Female', '1992-02-28', 'Christian', 'Catholic', NULL, 'Never Married', 161, 0, 'English', 'Self', 1, 81, 'Active', NOW(), NOW()),
(99, 'Charlotte Gomes', 'charlotte1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543308', 'Female', '1990-12-01', 'Christian', 'Catholic', NULL, 'Awaiting Divorce', 167, 0, 'English', 'Self', 1, 74, 'Active', NOW(), NOW()),
(100, 'Evelyn Pereira', 'evelyn1@example.com', NOW(), '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543309', 'Female', '1995-03-10', 'Christian', 'Protestant', NULL, 'Never Married', 159, 0, 'English', 'Parent', 1, 86, 'Active', NOW(), NOW()),


-- Insert profiles for all users
INSERT INTO profiles (
    user_id, about, highest_degree, institution, occupation, annual_income,
    employed_in, father_status, mother_status, siblings, family_type, family_values,
    financial_status, diet, drink, smoke, country, state, city, resident_status,
    has_horoscope, rashi, nakshatra, manglik, show_contact, visible_to,
    created_at, updated_at
)
SELECT
    id,
    CONCAT('About ', name, ' - ',
           CASE WHEN gender = 'Male' THEN 'He' ELSE 'She' END,
           ' is a professional looking for a life partner.'),

    CASE
        WHEN id % 5 = 0 THEN 'PhD'
        WHEN id % 3 = 0 THEN 'MTech'
        WHEN id % 2 = 0 THEN 'MBA'
        ELSE 'BSc'
    END AS highest_degree,

    CASE
        WHEN religion = 'Hindu' THEN
            CASE
                WHEN caste = 'Brahmin' THEN CONCAT('IIT ', (id % 3 + 1))
                WHEN caste = 'Patel' THEN 'Nirma University'
                ELSE 'Delhi University'
            END
        WHEN religion = 'Sikh' THEN 'Punjab University'
        WHEN religion = 'Muslim' THEN 'Aligarh Muslim University'
        ELSE 'St. Xavier''s College'
    END AS institution,

    -- occupation
    CASE
        WHEN id % 7 = 0 THEN 'Doctor'
        WHEN id % 5 = 0 THEN 'Engineer'
        WHEN id % 3 = 0 THEN 'Teacher'
        ELSE 'Business Professional'
    END AS occupation,

    -- annual_income (repeating occupation logic)
    CASE
        WHEN id % 7 = 0 THEN '25-30 Lakh'
        WHEN id % 5 = 0 THEN '15-20 Lakh'
        WHEN id % 3 = 0 THEN '6-8 Lakh'
        ELSE '10-15 Lakh'
    END AS annual_income,

    -- employed_in (repeating occupation logic again)
    CASE
        WHEN id % 7 = 0 THEN 'Private'
        WHEN id % 3 = 0 THEN 'Government'
        ELSE 'Business'
    END AS employed_in,

    CASE WHEN id % 4 = 0 THEN 'Retired' ELSE 'Business' END,
    'Homemaker',
    id % 4,
    CASE WHEN id % 3 = 0 THEN 'Joint' ELSE 'Nuclear' END,

    CASE
        WHEN religion = 'Hindu' AND caste = 'Brahmin' THEN 'Traditional'
        WHEN religion = 'Muslim' THEN 'Traditional'
        ELSE 'Moderate'
    END,

    CASE
        WHEN id % 7 = 0 THEN 'Affluent'
        WHEN id % 5 = 0 THEN 'Upper Middle Class'
        ELSE 'Middle Class'
    END,

    CASE
        WHEN religion = 'Jain' THEN 'Vegan'
        WHEN id % 4 = 0 THEN 'Non-Vegetarian'
        ELSE 'Vegetarian'
    END,

    CASE WHEN id % 5 = 0 THEN 'Yes' WHEN id % 3 = 0 THEN 'Occasionally' ELSE 'No' END,
    CASE WHEN id % 5 = 0 THEN 'Yes' WHEN id % 4 = 0 THEN 'Occasionally' ELSE 'No' END,

    'India',

    CASE
        WHEN caste = 'Patel' THEN 'Gujarat'
        WHEN caste = 'Brahmin' THEN
            CASE
                WHEN id % 3 = 0 THEN 'Uttar Pradesh'
                WHEN id % 3 = 1 THEN 'Maharashtra'
                ELSE 'Karnataka'
            END
        WHEN religion = 'Sikh' THEN 'Punjab'
        ELSE 'Delhi'
    END AS state,

    CASE
        WHEN (CASE
                  WHEN caste = 'Patel' THEN 'Gujarat'
                  WHEN caste = 'Brahmin' THEN
                      CASE
                          WHEN id % 3 = 0 THEN 'Uttar Pradesh'
                          WHEN id % 3 = 1 THEN 'Maharashtra'
                          ELSE 'Karnataka'
                      END
                  WHEN religion = 'Sikh' THEN 'Punjab'
                  ELSE 'Delhi'
              END) = 'Gujarat' THEN 'Ahmedabad'
        WHEN (CASE
                  WHEN caste = 'Brahmin' AND id % 3 = 0 THEN 'Uttar Pradesh'
                  WHEN caste = 'Brahmin' AND id % 3 = 1 THEN 'Maharashtra'
                  WHEN caste = 'Brahmin' THEN 'Karnataka'
                  ELSE NULL
              END) = 'Uttar Pradesh' THEN 'Lucknow'
        WHEN (religion = 'Sikh') THEN 'Chandigarh'
        ELSE 'Mumbai'
    END AS city,

    'Citizen',
    CASE WHEN id % 3 = 0 THEN 1 ELSE 0 END,

    CASE
        WHEN id % 12 = 0 THEN 'Aries'
        WHEN id % 12 = 1 THEN 'Taurus'
        WHEN id % 12 = 2 THEN 'Gemini'
        WHEN id % 12 = 3 THEN 'Cancer'
        WHEN id % 12 = 4 THEN 'Leo'
        WHEN id % 12 = 5 THEN 'Virgo'
        WHEN id % 12 = 6 THEN 'Libra'
        WHEN id % 12 = 7 THEN 'Scorpio'
        WHEN id % 12 = 8 THEN 'Sagittarius'
        WHEN id % 12 = 9 THEN 'Capricorn'
        WHEN id % 12 = 10 THEN 'Aquarius'
        ELSE 'Pisces'
    END,

    CASE
        WHEN id % 27 = 0 THEN 'Ashwini'
        WHEN id % 27 = 1 THEN 'Bharani'
        WHEN id % 27 = 2 THEN 'Krittika'
        WHEN id % 27 = 3 THEN 'Rohini'
        WHEN id % 27 = 4 THEN 'Mrigashira'
        WHEN id % 27 = 5 THEN 'Ardra'
        WHEN id % 27 = 6 THEN 'Punarvasu'
        WHEN id % 27 = 7 THEN 'Pushya'
        WHEN id % 27 = 8 THEN 'Ashlesha'
        WHEN id % 27 = 9 THEN 'Magha'
        WHEN id % 27 = 10 THEN 'Purva Phalguni'
        WHEN id % 27 = 11 THEN 'Uttara Phalguni'
        WHEN id % 27 = 12 THEN 'Hasta'
        WHEN id % 27 = 13 THEN 'Chitra'
        WHEN id % 27 = 14 THEN 'Swati'
        WHEN id % 27 = 15 THEN 'Vishakha'
        WHEN id % 27 = 16 THEN 'Anuradha'
        WHEN id % 27 = 17 THEN 'Jyeshtha'
        WHEN id % 27 = 18 THEN 'Mula'
        WHEN id % 27 = 19 THEN 'Purva Ashadha'
        WHEN id % 27 = 20 THEN 'Uttara Ashadha'
        WHEN id % 27 = 21 THEN 'Shravana'
        WHEN id % 27 = 22 THEN 'Dhanishta'
        WHEN id % 27 = 23 THEN 'Shatabhisha'
        WHEN id % 27 = 24 THEN 'Purva Bhadrapada'
        WHEN id % 27 = 25 THEN 'Uttara Bhadrapada'
        ELSE 'Revati'
    END,

    CASE WHEN id % 10 = 0 THEN 'Yes' WHEN id % 5 = 0 THEN 'Partial' ELSE 'No' END,
    1,
    CASE WHEN id % 3 = 0 THEN 'All' WHEN id % 3 = 1 THEN 'My Community' ELSE 'My Matches' END,
    NOW(),
    NOW()
FROM users;


-- Insert partner preferences
INSERT INTO partner_preferences (user_id, age_min, age_max, height_min, height_max, marital_status, religion, caste, education, occupation, country, created_at, updated_at)
SELECT
    users.id,  -- specify which table's id to use
    CASE
        WHEN users.gender = 'Male' THEN 22 ELSE 25
    END,
    CASE
        WHEN users.gender = 'Male' THEN 28 ELSE 32
    END,
    CASE
        WHEN users.gender = 'Male' THEN 150 ELSE 160
    END,
    CASE
        WHEN users.gender = 'Male' THEN 170 ELSE 180
    END,
    'Never Married',
    users.religion,
    CASE WHEN users.id % 3 = 0 THEN NULL ELSE users.caste END,
    CASE
        WHEN profiles.highest_degree = 'PhD' THEN 'Post Graduate'
        WHEN profiles.highest_degree = 'MTech' THEN 'Post Graduate'
        ELSE 'Graduate'
    END,
    profiles.occupation,
    'India',
    NOW(),
    NOW()
FROM users
JOIN profiles ON users.id = profiles.user_id;


-- Insert photos
INSERT INTO photos (id, user_id, path, is_primary, is_approved, created_at, updated_at)
SELECT
    id,
    id,
    CONCAT('profiles/', id, '/photo', (id % 3 + 1), '.jpg'),
    CASE WHEN id % 3 = 0 THEN 1 ELSE 0 END,
    1,
    NOW(),
    NOW()
FROM users;

-- Set one primary photo per user
UPDATE photos p1
JOIN (
    SELECT MIN(id) as min_id, user_id
    FROM photos
    GROUP BY user_id
) p2 ON p1.id = p2.min_id
SET p1.is_primary = 1;

-- Insert matches (creating various scenarios)
INSERT INTO user_matches (user_id, matched_user_id, match_score, status, created_at, updated_at)
-- Mutual matches
VALUES
(1, 51, 85, 'Accepted', NOW(), NOW()),
(51, 1, 85, 'Accepted', NOW(), NOW()),
-- [more mutual matches...]

-- Pending matches
(2, 52, 78, 'Pending', NOW(), NOW()),
(3, 53, 72, 'Pending', NOW(), NOW()),
-- [more pending matches...]

-- Rejected matches
(4, 54, 65, 'Rejected', NOW(), NOW()),
(5, 55, 60, 'Rejected', NOW(), NOW()),
-- [more rejected matches...]

-- One-way interests
(6, 56, 80, 'Pending', NOW(), NOW()),
(7, 57, 75, 'Pending', NOW(), NOW());
-- [more one-way interests...]

-- Insert interactions
INSERT INTO interactions (match_id, type, content, created_at, updated_at)
SELECT
    id,
    CASE
        WHEN id % 3 = 0 THEN 'View'
        WHEN id % 3 = 1 THEN 'Interest'
        ELSE 'Message'
    END,
    CASE
        WHEN id % 3 = 2 THEN CONCAT('Hello, I liked your profile! - ',
                                   (SELECT name FROM users WHERE id = (SELECT user_id FROM user_matches WHERE id = interactions.id)))
        ELSE NULL
    END,
    NOW(),
    NOW()
FROM user_matches
LIMIT 50;

-- Insert subscriptions
INSERT INTO subscriptions (user_id, plan_name, start_date, end_date, amount, payment_method, transaction_id, status, created_at, updated_at)
SELECT
    id,
    CASE
        WHEN id % 3 = 0 THEN 'Premium'
        WHEN id % 3 = 1 THEN 'VIP'
        ELSE 'Basic'
    END,
    NOW(),
    CASE
        WHEN id % 3 = 0 THEN DATE_ADD(NOW(), INTERVAL 3 MONTH)
        WHEN id % 3 = 1 THEN DATE_ADD(NOW(), INTERVAL 12 MONTH)
        ELSE NULL
    END,
    CASE
        WHEN id % 3 = 0 THEN 2999
        WHEN id % 3 = 1 THEN 9999
        ELSE 0
    END,
    CASE
        WHEN id % 4 = 0 THEN 'Credit Card'
        WHEN id % 4 = 1 THEN 'Debit Card'
        WHEN id % 4 = 2 THEN 'PayTM'
        ELSE 'UPI'
    END,
    CONCAT('txn_', FLOOR(RAND() * 1000000)),
    CASE
        WHEN id % 5 = 0 THEN 'Pending'
        WHEN id % 5 = 1 THEN 'Failed'
        ELSE 'Success'
    END,
    NOW(),
    NOW()
FROM users
LIMIT 20;
