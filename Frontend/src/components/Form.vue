<script setup>
import { ref, computed} from 'vue'
import { stringRegel, integerRegel } from './Rules.js'

// Definiere die Variablen für die Eingabefelder
const form = ref(null)
const formData = ref({
  regalbezeichnung: '',
  reihen: '',
  felder: '',
  ebenen: '',
  breite: '',
  hoehe: '',
  tiefe: ''
})

// Sendet die Daten an den Server
const sendeDaten = async () => {
  try {
    const response = await fetch('http://localhost:8000/api/racks/postRacks.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      // Umwandlung der Daten in JSON
      body: JSON.stringify({ ...formData.value })
    })

    if (!response.ok) {
      const errorText = await response.text()
      throw new Error(`Fehler beim Senden der Daten: ${response.status} - ${errorText}`)
    }

    const responseData = await response.json()
    console.log("Antwort vom Server", responseData)
    // Formular zurücksetzen
    form.value.reset()
    form.value.resetValidation()
  } catch (error) {
    console.error('Fehler beim Senden der Daten:', error)
  }
}

// Funktion, die beim Absenden des Formulars aufgerufen wird
const submitForm = async () => {
  if (form.value) {
    const { valid } = await form.value.validate()
    if (valid) {
      await sendeDaten()
    }
  }
}

// Berechnet die Anzahl der Lagerplätze
const anzahlLagerplaetze = computed(() => {
  return formData.value.reihen * formData.value.felder * formData.value.ebenen
})
</script>

<template>
  <v-sheet class="mx-auto" width="1200" :elevation="30">
    <v-form ref="form" @submit.prevent="submitForm" class="align-center">
      <v-text-field
          v-model="formData.regalbezeichnung"
          :rules="stringRegel"
          label="Regalbezeichnung"
      ></v-text-field>

      <v-text-field
          v-model="formData.reihen"
          :rules="integerRegel"
          label="Reihe(n)"
      ></v-text-field>

      <v-text-field
          v-model="formData.felder"
          :rules="integerRegel"
          label="Feld(er) pro Reihe"
      ></v-text-field>

      <v-text-field
          v-model="formData.ebenen"
          :rules="integerRegel"
          label="Ebene(en) pro Feld"
      ></v-text-field>

      <div class="dimension-row">
        <v-text-field
            v-model="formData.breite"
            :rules="integerRegel"
            label="Breite (cm)"
            class="fixed-size"
        ></v-text-field>

        <v-text-field
            v-model="formData.tiefe"
            :rules="integerRegel"
            label="Tiefe (cm)"
            class="fixed-size"
        ></v-text-field>

        <v-text-field
            v-model="formData.hoehe"
            :rules="integerRegel"
            label="Höhe (cm)"
            class="fixed-size"
        ></v-text-field>
      </div>

      <v-btn class="mt-2" type="submit" block>Lagerplatz erstellen</v-btn>
    </v-form>
    <div class="result">
      <p>Anzahl der Lagerplätze: {{ anzahlLagerplaetze }}</p>
    </div>
  </v-sheet>
</template>

<style scoped>
.fixed-size {
  width: 300px; /* Feste Breite für die Textfelder */
}
.dimension-row {
  display: flex;
  gap: 16px; /* Abstand zwischen den Feldern */
}
.result {
  margin-top: 20px;
  font-size: 18px;
  font-weight: bold;
  text-align: center;
}
</style>