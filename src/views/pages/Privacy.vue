<template>
  <div class="container flex">
    <Menu :model="menuItems" @item-click="scrollToContent" class="sidebar"></Menu>
    <ScrollPanel class="content" ref="content">
      <section v-for="section in sections" :id="section.id" :key="section.id">
        <h2>{{ section.title }}</h2>
        <template v-if="section.id !== 'contact'">
          <p v-html="section.content"></p>
          <ul v-if="section.items">
            <li v-for="item in section.items" :key="item">{{ item }}</li>
          </ul>
        </template>
        <p v-else v-html="section.content"></p>
      </section>

    </ScrollPanel>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const sections = ref([
  {
    id: "information", title: "1. Information We Collect", content: "We collect personal information that you provide to us, such as:", items: [
      "Name",
      "Address",
      "Contact information (e.g., email address, phone number)",
      "Passwords and security data",
      "Payment information",
      "Social media login data"
    ]
  },
  {
    id: "usage", title: "2. How We Use Information", content: "We use the personal information collected via our services for the following purposes:", items: [
      "To provide and maintain our services",
      "To notify you about changes to our services",
      "To allow you to participate in interactive features of our services",
      "To provide customer support",
      "To gather analysis or valuable information so that we can improve our services",
      "To monitor the usage of our services",
      "To detect, prevent and address technical issues"
    ]
  },
  {
    id: "shared", title: "3. Will Your Information Be Shared With Anyone?", content: "We only share and disclose your information with the following third parties:", items: [
      "Service providers who help us provide our services, such as hosting providers, payment processors, and email service providers",
      "Government agencies as required by law",
      "Other third parties when you give your consent or direct us to do so"
    ]
  },
  { id: "tracking", title: "4. Cookies and Other Tracking Technologies", content: "We may use cookies and similar tracking technologies to access or store information..." },
  { id: "safe", title: "5. How We Keep Your Information Safe", content: "We are committed to protecting the security of your personal information..." },
  {
    id: "rights", title: "6. What Are Your Privacy Rights?", content: "You have certain rights under applicable data protection laws, including the right to:", items: [
      "Access, update or delete your personal information",
      "Object to the processing of your personal information",
      "Withdraw your consent at any time",
      "Lodge a complaint with a supervisory authority"
    ]
  },
  { id: "updates", title: "7. Updates to This Policy", content: "We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page." },
  { id: "contact", title: "8. How to Contact Us", content: "If you have questions or comments about this Privacy Policy, you may email us at <a href='mailto:anahawislandviewresort@gmail.com'>anahawislandviewresort@gmail.com</a>" }
]);

const menuItems = ref([
  { label: "1. Information We Collect", id: "information" },
  { label: "2. How We Use Information", id: "usage" },
  { label: "3. Will Your Information Be Shared With Anyone?", id: "shared" },
  { label: "4. Cookies and Other Tracking Technologies", id: "tracking" },
  { label: "5. How We Keep Your Information Safe", id: "safe" },
  { label: "6. What Are Your Privacy Rights?", id: "rights" },
  { label: "7. Updates to This Policy", id: "updates" },
  { label: "8. How to Contact Us", id: "contact" },
]);

function scrollToContent(event) {
  const sectionId = event.item.id;
  const element = document.getElementById(sectionId);
  if (element) {
    const contentElement = this.$refs.content.$el;
    contentElement.scrollTop = element.offsetTop - contentElement.offsetTop; // Adjust if there is any header or fixed elements
  }
}
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
