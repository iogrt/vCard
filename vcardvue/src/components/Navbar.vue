<template>
  <div class="bg-light">
    <nav class="navbar navbar-expand-md navbar-light bg-light mb-3 container">
        <router-link :to="{name:'Home'}" class="navbar-brand bg-primary text-light px-4 rounded-3" href="#">vCard</router-link>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <router-link class="nav-link" :to="{name:'CardCreate'}">Add Card </router-link>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
            </ul>
        </div>
    </nav>
  </div>
</template>
