<template>
  <v-container
    class="w-75 justify-content-center mx-auto mt-5 align-items-center"
  >
  <!-- Title Header -->
  <v-row>
      <v-col cols>
          <h1 class="text-center">Lead CRUD Application</h1>
      </v-col>
  </v-row>
    <!-- Search Text Input -->
    <v-card elevation="24" class="pa-12">
        <v-row>
        <v-col md="4" sm="12">
            <v-text-field
            label="Search..."
            solo
            v-model="search"
            append-icon="mdi-magnify"
            ></v-text-field>
        </v-col>
        </v-row>

        <!-- Datatable -->
        <v-row align="center">
        <v-col cols>
            <v-data-table
            :search="search"
            :headers="headers"
            :items="leads"
            fixed-header
            class="elevation-1"
            >
            <template v-slot:[`item.tags`]="{ item }">
                <v-chip class="mr-2" label v-for="tag in item.tags" :color="randomColor()" :key="tag.id">{{
                tag.name
                }}</v-chip>
            </template>

            <template v-slot:top>
                <v-toolbar flat>
                <v-toolbar-title>Leads</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>

                <!-- Create Lead Dialog -->
                <v-dialog v-model="dialog" max-width="500px" persistent>
                    <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="primary"
                        dark
                        class="mb-2"
                        v-bind="attrs"
                        v-on="on"
                    >
                        Create Lead
                    </v-btn>
                    </template>
                    <v-card>
                    <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container>
                        <v-form ref="leadForm" v-model="valid">
                            <v-row>
                            <v-col cols="12">
                                <v-text-field
                                v-model="editedItem.email"
                                label="Email"
                                :rules="[rules.required]"
                                @input="errors.email = null"
                                :error="errors.email ? true : false"
                                :error-count="1"
                                :error-messages="errors.email"
                                ></v-text-field>
                            </v-col>
                            </v-row>

                            <v-row>
                            <v-col cols="12">
                                <v-autocomplete
                                v-model="editedItem.tags"
                                :items="tags"
                                :rules="[rules.autocompleteRequired]"
                                @input="errors.tags = null"
                                :error="errors.tags ? true : false"
                                :error-count="1"
                                :error-messages="errors.tags"
                                item-text="name"
                                return-object
                                label="Tags"
                                multiple
                                chips
                                >
                                </v-autocomplete>
                            </v-col>
                            </v-row>
                            <v-row>
                            <v-col cols="12">
                                <v-textarea
                                v-model="editedItem.notes"
                                :rules="[rules.required]"
                                @input="errors.notes = null"
                                :error="errors.email ? true : false"
                                :error-count="1"
                                :error-messages="errors.notes"
                                no-resize
                                filled
                                label="Notes"
                                ></v-textarea>
                            </v-col>
                            </v-row>
                        </v-form>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="close">
                        Cancel
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="save">
                        Save
                        </v-btn>
                    </v-card-actions>
                    </v-card>
                </v-dialog>

                    <v-divider class="mx-4" inset vertical></v-divider>
                    
                    <!-- Create Tag Dialog -->
                <v-dialog v-model="dialogTag" max-width="500px" persistent>
                    <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="green lighten-1"
                        dark
                        class="mb-2"
                        v-bind="attrs"
                        v-on="on"
                    >
                        Create Tag
                    </v-btn>
                    </template>
                    <v-card>
                    <v-card-title>
                        <span class="headline">Create Tag</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container>
                        <v-form ref="tagForm" v-model="validTag">
                            <v-row>
                            <v-col cols="12">
                                <v-text-field
                                v-model="tag_name"
                                label="Tag Name"
                                :rules="[rules.required]"
                                @input="errorTag.name = null"
                                :error="errorTag.name ? true : false"
                                :error-count="1"
                                :error-messages="errorTag.name"
                                ></v-text-field>
                            </v-col>
                            </v-row>
                        </v-form>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeTag">
                        Cancel
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="saveTag">
                        Save
                        </v-btn>
                    </v-card-actions>
                    </v-card>
                </v-dialog>



                <!-- Delete Dialog -->
                <v-dialog v-model="dialogDelete" max-width="500px" persistent>
                    <v-card>
                    <v-card-title class="headline"
                        >Are you sure you want to delete this item?</v-card-title
                    >
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="closeDelete"
                        >Cancel</v-btn
                        >
                        <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                        >OK</v-btn
                        >
                        <v-spacer></v-spacer>
                    </v-card-actions>
                    </v-card>
                </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template>
            </v-data-table>
        </v-col>
        </v-row>
    </v-card>
  </v-container>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      search: "",
      tags: [],
      leads: [],
      headers: [
        {
          text: "ID",
          align: "start",
          value: "id",
        },
        { text: "Email", value: "email" },
        { text: "Tags", value: "tags", sortable: false },
        { text: "Notes", value: "notes" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      editedIndex: -1,
      dialog: false,
      dialogDelete: false,
      rules: {
        required: (value) => !!value || "This field is required.",
        autocompleteRequired: (value) =>
          value.length > 0 || "This field is required.",
        email: (value) =>
          /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
            value
          ) || "Invalid email format.",
      },
      valid: false,
      dialogTag: false,
      validTag: false,
      tag_name: '',

      defaultItem: {
        email: "",
        tags: [],
        notes: "",
      },
      editedItem: {
        email: "",
        tags: [],
        notes: "",
      },

      errors: {},
      errorTag: {},
    };
  },
  created() {
    this.initiate();
  },
  methods: {
    initiate() {
      axios.get("/all-leads").then((res) => {
        this.leads = res.data;
      });

      axios.get("/all-tags").then((res) => {
        this.tags = res.data;
      });
    },
    editItem(item) {
      console.log("item", item);

      this.editedIndex = this.leads.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    close() {
      this.$refs.leadForm.resetValidation();
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    deleteItem(item) {
      this.editedIndex = this.leads.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      axios
        .delete(`/lead/${this.editedItem.id}`)
        .then((res) => {
          this.closeDelete();
          this.initiate();
        })
        .catch((err) => {});
    },
    save() {
      if (this.editedIndex > -1) {
        if (this.$refs.leadForm.validate()) {
          axios
            .put(`/lead/${this.editedItem.id}`, {
              email: this.editedItem.email,
              notes: this.editedItem.notes,
              tags: this.editedItem.tags,
            })
            .then((res) => {
              this.initiate();
              this.close();
            })
            .catch(err => {
                if(err.response.status === 422){
                    this.errors = err.response.data
                }
            })
        }
      } else {
        if (this.$refs.leadForm.validate()) {
          axios
            .post("/lead", {
              email: this.editedItem.email,
              notes: this.editedItem.notes,
              tags: this.editedItem.tags,
            })
            .then((res) => {
              this.initiate();
              this.close();
            })
            .catch(err => {
                if(err.response.status === 422){
                    this.errors = err.response.data

                }
                
                
            })
        }
      }
    },

    closeTag(){
        this.dialogTag = false
        this.tag_name = ''
        this.$refs.tagForm.resetValidation();
    },
    saveTag(){
        axios.post('/tag', {
            name: this.tag_name
        })
        .then(res => {
            this.initiate()
            this.closeTag()
        })
        .catch(err => {
            if(err.response.status === 422){
                this.errorTag = err.response.data
            }
        })
    },
    randomColor(){
        let randomColorHex =  `#${((Math.random() * 0xffffff) << 0).toString(16)}`
        return randomColorHex
    }
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Lead" : "Edit Lead";
    },
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },
};
</script>
