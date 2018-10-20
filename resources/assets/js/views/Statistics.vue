<template>
    <div class="container">
        <div class="row mt-1">
            <div class="col-sm-12 my-3">
                <p class="h2">Select Period (Default month)</p>
                <rangedate-picker class=""
                  @selected="setDateRange"
                  i18n="EN"
                  v-bind:initRange="initRange"
                  :presetRanges="presetRanges"
                  ></rangedate-picker>
            </div>
            <!-- <div class="col-sm-12">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-info active">
                        <input type="radio" name="options" id="option1" autocomplete="off" checked>Month
                    </label>
                    <label class="btn btn-outline-info">
                        <input type="radio" name="options" id="option2" autocomplete="off"> 3 Monthes
                    </label>
                    <label class="btn btn-outline-info">
                        <input type="radio" name="options" id="option3" autocomplete="off"> Year
                    </label>
                </div>
            </div> -->
        </div>
    </div>
</template>

<script>
// import '../bootstrap';
Vue.use(BootstrapVue, VueRangedatePickerWinslow, moment);

// import AddProject from "../components/AddProject.vue";
// import ActiveSprints from "../components/ActiveSprints.vue";
import Preloader from "../components/Preloader.vue";

export default {
  // props: {
  //     programmers: Array
  // },
  components: {
    // "add-project": AddProject,
    // "active-sprints": ActiveSprints,
    preloader: Preloader,
    "rangedate-picker": VueRangedatePickerWinslow
  },

  data() {
    return {
      //   showAddNew: false,
      items: [],
      dateStart: false,
      dateEnd: false,
      showLoader: false,
      // startDate: "2017-09-05",
      // endDate: "2017-09-15",
      initRange: {
        start: moment()
         .subtract(1, 'months').add(1,'day').toDate(),
        end: moment().add(1,'day').toDate()
        // 'start': new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - 5, 0, 0),
        // 'end': new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() + 1, 0, 0)
      },
      presetRanges: {
        thisMonth: function () {
            return {
              label: 'This Month',
              active: false,
              dateRange: {
                start: moment().startOf('month').add(1,'day').toDate(),
                end: moment().add(1,'day').toDate()
              }
            }
          },
        lastMonth: function () {
            return {
              label: 'Last Month',
              active: false,
              dateRange: {
                start: moment().subtract(1, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
        },
        last3Month: function () {
            return {
              label: 'Last 3 Month',
              active: false,
              dateRange: {
                start: moment().subtract(3, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
          },
        lastHalfYear: function () {
            return {
              label: 'Last 6 Monthes',
              active: false,
              dateRange: {
                start: moment().subtract(6, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
          },
        lastYear: function () {
            return {
              label: 'Last Year',
              active: false,
              dateRange: {
                start: moment().subtract(12, 'months').startOf('month').add(1,'day').toDate(),
                end: moment().subtract(1, 'months').endOf('month').add(1,'day').toDate(),
              }
            }
          },
        // last3MonthesWithCurrent: function () {
        //     return {
        //       label: 'Last 3 Monthes Include Current',
        //       active: false,
        //       dateRange: {
        //         start: moment().subtract(1, 'months').add(1,'day').toDate(),
        //         end: moment().add(1,'day').toDate()
        //       }
        //     }
        //   },
      }

    };
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      //   return this.fields.filter(f => f.sortable).map(f => {
      //     return { text: f.label, value: f.key };
      //   });
    }
  },
  methods: {
    // info (item, index, button) {
    //   this.modalInfo.title = `Row index: ${index}`
    //   this.modalInfo.content = JSON.stringify(item, null, 2)
    //   this.$root.$emit('bv::show::modal', 'modalInfo', button)
    // },
    // resetModal() {
    //   this.modalInfo.title = "";
    //   this.modalInfo.content = "";
    // },
    // onFiltered(filteredItems) {
    //   // Trigger pagination to update the number of buttons/pages due to filtering
    //   this.totalRows = filteredItems.length;
    //   this.currentPage = 1;
    // },
    // addProject(customer) {
    //   this.items.push(customer);
    //   this.showAddNew = false;
    // },
    // confirmDelete(item, index, button) {
    //   this.modalInfo.title = `Delete Project`;
    //   this.modalInfo.content = `Are you Sure Want to Delete ${item.name}?`;
    //   this.modalInfo.item = item;
    //   this.$root.$emit("bv::show::modal", "deleteModal", button);
    // },
    // handleDelete() {
    //   // this.$root.$emit("bv::hide::modal", "deleteModal");
    //   // console.log(this.modalInfo.item);
    //   axios.delete(`api/projects/${this.modalInfo.item.id}`).then(
    //     ({ data }) => {
    //       this.items = this.items.filter(e => e.id !== data.id);
    //       this.hideDeleteModal();
    //     },
    //     err => comsole.log(err)
    //   );
    // },
    // hideDeleteModal() {
    //   this.$root.$emit("bv::hide::modal", "deleteModal");
    // }
    setDateRange(dates) {
      console.log(dates);
      this.dateStart = dates.start;
      this.dateEnd = dates.end;
    },

    // initialRange(){
    //     const n = new Date();
    //     const start = new Date(n.getFullYear(), n.getMonth(), n.getDate() - 5);
    //     const end = new Date(n.getFullYear(), n.getMonth(), n.getDate() + 1);
    //     return {
    //         start: start,
    //         end: end
    //     }
    // },

    moment: function() {
      return moment();
    }
  },
  created() {
    //make an ajax request to our server
    this.showLoader = true;
    axios
      .get("/api/projects")
      .then(({ data }) => {
        this.items = data;
        // this.totalRows = data.length;
      })
      .then(() => (this.showLoader = false));
  }
};
</script>

<!-- table-complete-1.vue -->