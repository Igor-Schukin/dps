<style scoped>
</style>

<template>
    <div class="container-fluid mt-5">

        <div v-show="okMessage" class="alert alert-success text-center">
            {{ okMessage }}
            <p class="pt-3 mb-0 text-center">
                <button class="btn btn-primary" @click.prevent="doRefresh">Reģistrēt nākamā studenta</button>
            </p>
        </div>

        <template v-if="!okMessage">

            <div v-show="errMessage" class="alert alert-danger text-center alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                {{ errMessage }}
            </div>

            <form>

                <div v-for="(field, id) in fields" :key="`field-${id}`" class="row mb-3">
                    <label :for="`field-${id}`" class="col-sm-2 form-label">{{ field.title }}</label>
                    <div class="col" >
                        <input 
                            v-if="field.type != 'select'" 
                            class="form-control" 
                            :type="field.type" 
                            :id="`field-${id}`" 
                            v-model.trim="field.data.value"
                            :class="{'is-invalid': field.error.value}"
                            @input="field.error.value=false"
                        >
                        <select v-else class="form-select" v-model="field.data.value">
                            <option v-for="grp in field.values" :key="`group-${grp}`" :value="grp">{{ grp }}</option>
                        </select>
                        <div v-if="field.error.value" class="invalid-feedback">{{ field.error.value }}</div>
                    </div>
                </div>

                <button class="btn btn-primary" @click.prevent="doRegister">Saglabat</button>

            </form>	
        </template>	
    
    </div>
</template>

<script setup>
    import { ref } from 'vue';

    const fields = {
        group:   { type: "select", data: ref("C2"), title: "DPS grupa", error: ref(""), values: ["A1", "A2", "B1", "B2", "C1", "C2"] },
        name:    { type: "text",   data: ref(""),   title: "Vārds", error: ref("")},
        surname: { type: "text",   data: ref(""),   title: "Uzvārds", error: ref("")},
        school:  { type: "text",   data: ref(""),   title: "Skona", error: ref("")},
        year:    { type: "select", data: ref("12"), title: "Klase", values: ["6", "7", "8", "9", "10", "11", "12"], error: ref("")},
        email:   { type: "email",  data: ref(""),   title: "E-mail adrese", error: ref("")},
        phone:   { type: "text",   data: ref(""),   title: "Telefona numurs", error: ref("")},
    };

    const okMessage = ref(false);
    const errMessage = ref(false);

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
    }

    async function doRegister() {
        var data = {}; for(const field in fields) data[field] = fields[field].data.value;
        errMessage.value = false;
        okMessage.value = false;
        let response = await fetch('register.php', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        if (response.status != 200) {
            errMessage.value = await response.text();
            return;
        }
        let d = await response.json();
        console.log(d);   
        okMessage.value = "Ok";
    }

    function doRefresh() {
        errMessage.value = false;
        okMessage.value = false;
        fields.name.data.value = "";
        fields.surname.data.value = "";
    }


</script>
