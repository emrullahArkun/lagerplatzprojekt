<script>
export default {
  data() {
    return {
      tableData: [], // Daten für die Tabelle
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Regalbezeichnung', value: 'regalbezeichnung' },
        { text: 'Reihen', value: 'reihen' },
        { text: 'Felder', value: 'felder' },
        { text: 'Ebenen', value: 'ebenen' },
        { text: 'Breite', value: 'breite' },
        { text: 'Tiefe', value: 'tiefe' },
        { text: 'Höhe', value: 'hoehe' },
      ],
    };
  },
  async created() {
    try {
      const response = await fetch('http://localhost:8000/api/racks/getRacks.php');
      if (!response.ok) {
        throw new Error(`Fehler beim Abrufen der Daten: ${response.status}`);
      }
      this.tableData = await response.json();
      console.log("Daten erfolgreich abgerufen:", this.tableData);
    } catch (error) {
      console.error('Fehler beim Abrufen der Daten:', error);
    }
  },
};
</script>

<template>
  <v-sheet class="mx-auto" width="1200" :elevation="30">
    <v-data-table
        :headers="headers"
        :items="tableData"
        class="elevation-1"
    ></v-data-table>
  </v-sheet>
</template>
