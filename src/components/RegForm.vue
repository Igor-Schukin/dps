<style scoped>
</style>

<template>
    <div class="container-fluid mt-5">

        <form>

            <div v-for="(field, id) in fields" :key="`field-${id}`" class="row mb-3">
                <label :for="`field-${id}`" class="col-sm-2 form-label">{{ field.title }}</label>
                <input v-if="field.type != 'select'" class="col form-select" :type="field.type" :id="`field-${id}`" v-model="field.data.value">
                <select v-else class="col form-select">
                    <option v-for="grp in field.values" :key="`group-${grp}`" :value="grp">{{ grp }}</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Saglabat</button>
<!--
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
-->
        </form>		
    
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    const fields = {
        group:   { type: "select", data: ref("C2"), title: "DPS grupa", values: ["A1", "A2", "B1", "B2", "C1", "C2"] },
        name:    { type: "text",   data: ref(""),   title: "Vārds"},
        surname: { type: "text",   data: ref(""),   title: "Uzvārds"},
        school:  { type: "text",   data: ref(""),   title: "Skona"},
        year:    { type: "text",   data: ref(""),   title: "Klase"},
        email:   { type: "email",  data: ref(""),   title: "E-mail adrese"},
        phone:   { type: "text",   data: ref(""),   title: "Telefona numurs"},
    };
</script>
