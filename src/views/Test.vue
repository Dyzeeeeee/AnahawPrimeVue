<template>
  <div>
    <button @click="generatePDF">Download PDF</button>
    <div id="content-to-print"
      style="background: white; padding: 20px; width: 500px; position: absolute; left: -10000px; top: -10000px;">
      <h1 style="color: blue;">Test Print PDF</h1>
      <p>This is a paragraph in the PDF, styled with simple CSS.</p>
    </div>
  </div>
</template>

<script setup>
import { jsPDF } from "jspdf";
import domtoimage from "dom-to-image-more";
import { ref } from 'vue';

const generatePDF = async () => {
  const content = document.getElementById('content-to-print');

  try {
    const imgData = await domtoimage.toPng(content);
    const pdf = new jsPDF({
      orientation: 'p',
      unit: 'px',
      format: 'a4'
    });

    // Calculate the width and height to maintain aspect ratio
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    pdf.save('test.pdf');
  } catch (error) {
    console.error('Error generating PDF', error);
  }
};
</script>
