<template>
  <div class="privacy-policy">
    <h1>Privacy Policy</h1>
    <p>Last updated: April 12, 2024</p>
    <section>
      <!-- Your privacy policy content -->
      <h2>1. Information We Collect</h2>
      <p>
        We collect personal information that you provide to us such as name, address,
        contact information, passwords, and security data, payment information, and social
        media login data...
      </p>

      <h2>2. How We Use Your Information</h2>
      <p>
        We use personal information collected via our Services for a variety of business
        purposes described below...
      </p>

      <h2>3. Will Your Information Be Shared With Anyone?</h2>
      <p>
        We only share and disclose your information with the following third parties...
      </p>

      <h2>4. Cookies and Other Tracking Technologies</h2>
      <p>
        We may use cookies and similar tracking technologies to access or store
        information...
      </p>

      <h2>5. How We Keep Your Information Safe</h2>
      <p>
        We aim to protect your personal information through a system of organizational and
        technical security measures...
      </p>

      <h2>6. What Are Your Privacy Rights?</h2>
      <p>You have certain rights under applicable data protection laws...</p>

      <h2>7. Updates to This Policy</h2>
      <p>We may update this privacy policy from time to time...</p>

      <h2>8. How to Contact Us</h2>
      <p>
        If you have questions or comments about this policy, you may email us or contact
        us by post...
      </p>
    </section>
  </div>
</template>

<script>
export default {
  name: "PrivacyPolicy",
};
</script>

<style scoped>
.privacy-policy {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background: #fff;
  border-radius: 8px;
}

.privacy-policy h1 {
  font-size: 24px;
  color: #333;
}

.privacy-policy p {
  font-size: 16px;
  color: #666;
  line-height: 1.6;
}

.privacy-policy h2 {
  font-size: 20px;
  color: #444;
  margin-top: 20px;
}

@media (max-width: 768px) {
  .privacy-policy {
    padding: 10px;
  }

  .privacy-policy h1 {
    font-size: 22px;
  }

  .privacy-policy h2 {
    font-size: 18px;
  }

  .privacy-policy p {
    font-size: 14px;
  }
}
</style>
