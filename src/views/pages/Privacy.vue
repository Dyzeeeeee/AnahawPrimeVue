<template>
  <div class="layout-topbar flex justify-content-center">

  </div>
  <div class="flex pt-8" >
    <div class="sidebar pt-8">
      <ul>
        <li><a href="#information">1. Information We Collect</a></li>
        <li><a href="#usage">2. How We Use Information</a></li>
        <li><a href="#shared">3. Will Your Information Be Shared With Anyone?</a></li>
        <li><a href="#tracking">4. Cookies and Other Tracking Technologies</a></li>
        <li><a href="#safe">5. How We Keep Your Information Safe</a></li>
        <li><a href="#rights">6. What Are Your Privacy Rights?</a></li>
        <li><a href="#updates">7. Updates to This Policy</a></li>
        <li><a href="#contact">8. How to Contact Us</a></li>
      </ul>
    </div>
    <ScrollPanel class="content" ref="content">
      <section id="information">
        <h2>1. Information We Collect</h2>
        <p>We collect personal information that you provide to us, such as:</p>
        <ul>
          <li>Name</li>
          <li>Address</li>
          <li>Contact information (e.g., email address, phone number)</li>
          <li>Passwords and security data</li>
          <li>Payment information</li>
          <li>Social media login data</li>
        </ul>
      </section>
      <section id="usage">
        <h2>2. How We Use Information</h2>
        <p>We use the personal information collected via our services for the following purposes:</p>
        <ul>
          <li>To provide and maintain our services</li>
          <li>To notify you about changes to our services</li>
          <li>To allow you to participate in interactive features of our services</li>
          <li>To provide customer support</li>
          <li>To gather analysis or valuable information so that we can improve our services</li>
          <li>To monitor the usage of our services</li>
          <li>To detect, prevent and address technical issues</li>
        </ul>
      </section>
      <section id="shared">
        <h2>3. Will Your Information Be Shared With Anyone?</h2>
        <p>We only share and disclose your information with the following third parties:</p>
        <ul>
          <li>Service providers who help us provide our services, such as hosting providers, payment processors, and
            email service providers</li>
          <li>Government agencies as required by law</li>
          <li>Other third parties when you give your consent or direct us to do so</li>
        </ul>
      </section>
      <section id="tracking">
        <h2>4. Cookies and Other Tracking Technologies</h2>
        <p>We may use cookies and similar tracking technologies to access or store information...</p>
      </section>
      <section id="safe">
        <h2>5. How We Keep Your Information Safe</h2>
        <p>We are committed to protecting the security of your personal information...</p>
      </section>
      <section id="rights">
        <h2>6. What Are Your Privacy Rights?</h2>
        <p>You have certain rights under applicable data protection laws, including the right to:</p>
        <ul>
          <li>Access, update or delete your personal information</li>
          <li>Object to the processing of your personal information</li>
          <li>Withdraw your consent at any time</li>
          <li>Lodge a complaint with a supervisory authority</li>
        </ul>
      </section>
      <section id="updates">
        <h2>7. Updates to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new
          Privacy Policy on this page.</p>
      </section>
      <section id="contact">
        <h2>8. How to Contact Us</h2>
        <p>If you have questions or comments about this Privacy Policy, you may email us at <a
            href="mailto:anahawislandviewresort@gmail.com">anahawislandviewresort@gmail.com</a></p>
      </section>
    </ScrollPanel>
  </div>
</template>


<script setup>

</script>

<style scoped>
.container {
  display: flex;
  max-width: 1200px;
  margin: auto;
  font-family: 'Arial', sans-serif;
  background-color: #fff;
  /* White background */
}

.sidebar {
  flex: 0 0 250px;
  background-color: #fff;
  padding: 20px;
  height: 100vh;
  overflow-y: auto;
  position: sticky;
  top: 0;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar ul {
  list-style-type: none;
  /* No bullets */
  padding: 0;
  /* No padding */
}

.sidebar li {
  margin-bottom: 10px;
  /* Space between items */
}

.sidebar a {
  text-decoration: none;
  /* No underline */
  color: #333;
  /* Dark text for readability */
  font-weight: bold;
}

.sidebar a:hover {
  color: #000;
  /* Darker text on hover */
}

.content {
  flex: 1;
  /* Takes up remaining space */
  padding: 20px;
  overflow-y: auto;
  /* Scrollable content */
  background-color: #fff;
  /* White background */
}

.content h2 {
  color: #333;
  /* Dark text for headers */
  margin-top: 0;
}

.content p,
.content li {
  color: #666;
  /* Slightly lighter text for readability */
  line-height: 1.6;
  /* Spacing for readability */
}

.sidebar {
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  /* Add a small shadow to the sidebar for depth */
}

.active-section {
  color: #0056b3;
  /* Highlight color */
}

.content ul {
  padding-left: 20px;
  /* Indent list items */
}

.content ul li {
  margin-bottom: 5px;
  /* Space between list items */
}

.content section {
  margin-bottom: 40px;
  /* Space at the bottom of sections */
}
</style>
