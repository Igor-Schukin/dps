<style scoped>
</style>

<template> 

    <div class="container mx-0">
        <div class="container-fluid rounded mx-5 mb-3 p-3 bg-primary text-white">
            <h2>Daugavpils Programmētāju Skola</h2>
            <p class="m-0">studentu reģistrācija</p>
        </div>

        <div class="container-fluid mx-5 my-0 px-0">

            <div v-show="okMessage" class="alert alert-success text-center">
                Students <strong>{{ fields.name.data.value }} {{ fields.surname.data.value }}</strong> tika veiksmīgi reģistrēts Daugavpils Programmētāju Skolas <strong>{{ fields.group.data.value }}</strong> grupā
                <p class="pt-3 mb-0 text-center">
                    <button class="btn btn-primary" @click.prevent="doRefresh">Reģistrēt nākamo studentu</button>
                </p>
            </div>

            <template v-if="!okMessage">

                <div v-show="errMessage" class="alert alert-danger text-center alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    Neizdevās reģistrēt studentu <strong>{{ fields.name.data.value }} {{ fields.surname.data.value }}</strong> Daugavpils Programmētāju Skolas <strong>{{ fields.group.data.value }}</strong> grupā:<br>
                    {{ errMessage }}
                </div>

                <form>

                    <div v-for="(field, id) in fields" :key="`field-${id}`" class="form-floating row mb-3">
                        <input 
                            v-if="field.type != 'select'" 
                            class="form-control" 
                            :type="field.type" 
                            :id="`field-${id}`" 
                            v-model.trim="field.data.value"
                            :class="{'is-invalid': field.error.value}"
                            :placeholder="field.title"
                            @input="field.error.value=false"
                            :disabled="wait"
                        >
                        <select v-else class="form-select" v-model="field.data.value" :disabled="wait">
                            <option v-for="grp in field.values" :key="`group-${grp}`" :value="grp">{{ grp }}</option>
                        </select>
                        <div v-if="field.error.value" class="invalid-feedback">{{ field.error.value }}</div>

                        <label :for="`field-${id}`" class="form-label">{{ field.title }}</label>
                    </div>

                    <button v-if="!wait" class="btn btn-primary" @click.prevent="doRegister">Reģistrēties</button>

                    <button v-else class="btn btn-primary" disabled>
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Reģistrējas...
                    </button>

                </form>	
            </template>	
        
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';

    const fields = {
        group:   { type: "select", data: ref("C2"), title: "DPS grupa", error: ref(""), values: ["A1", "A2", "B1", "B2", "C1", "C2"] },
        name:    { type: "text",   data: ref(""),   title: "Vārds", error: ref("")},
        surname: { type: "text",   data: ref(""),   title: "Uzvārds", error: ref("")},
        school:  { type: "text",   data: ref(""),   title: "Skola", error: ref("")},
        year:    { type: "select", data: ref("12"), title: "Klase", values: ["4", "5", "6", "7", "8", "9", "10", "11", "12"], error: ref("")},
        email:   { type: "email",  data: ref(""),   title: "E-mail adrese", error: ref("")},
        phone:   { type: "text",   data: ref(""),   title: "Telefona numurs", error: ref("")},
    };

    const okMessage = ref(false);
    const errMessage = ref(false);
    const wait = ref(false);

    const urlParams = new URLSearchParams(window.location.search);
    const group = urlParams.get('group');
    let y = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'].indexOf(group);
    if (y >= 0) {
        fields.group.data.value = group;
        fields.year.data.value = 6 + y;
    }

    function isValidEmail(email) 
    {
        return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email);
    }

    function validate() {
        for(const field in fields) fields[field].error.value = false;
        if (!fields.name.data.value) fields.name.error.value = "Jums jāievada savs vārds";
        if (!fields.surname.data.value) fields.surname.error.value = "Jums jāievada savs uzvārds";
        if (!fields.school.data.value) fields.school.error.value = "Jums jāievada savas skolas nisaukums";
        if (!fields.email.data.value) fields.email.error.value = "Jums jāievada sava kontakta e-pasta adrese";
        else if (!isValidEmail(fields.email.data.value)) fields.email.error.value = "Lūdzu ievadiet pareizo e-pasta adresi";
        for(const field in fields) if (fields[field].error.value) return false;
        return true;
    }

    async function doRegister() {
        if (!validate()) return;
        var data = {}; for(const field in fields) data[field] = fields[field].data.value;
        errMessage.value = false;
        okMessage.value = false;
        wait.value = true;
        let response = await fetch('register.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        if (response.status != 200) errMessage.value = await response.text();
        else  okMessage.value = "Ok";
        wait.value = false;
    }

    function doRefresh() {
        errMessage.value = false;
        okMessage.value = false;
        fields.name.data.value = "";
        fields.surname.data.value = "";
        fields.email.data.value = "";
        fields.phone.data.value = "";
    }


</script>
