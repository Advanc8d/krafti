<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="contacts__content">
        <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <div class="row">
                <div class="col-12">
                  <h2 class="section__title">Контакты</h2>
                </div>
              </div>
              <div class="row contacts__info">
                <div class="col-lg-6 col-12">
                  <div class="contacts__links-block">
                    <div class="contacts__text mb-2">
                      <p v-html="$md.render($app.settings('contacts_text_top'))" />
                    </div>
                    <ul class="contacts__list">
                      <li class="contact__item">
                        <a :href="`mailto:${$app.settings('email')}`" class="contact__link" target="_blank">
                          Почта
                        </a>
                      </li>
                      <li class="contact__item">
                        <a :href="$app.settings('link_instagram')" class="contact__link" target="_blank">
                          Instagram
                        </a>
                      </li>
                      <li class="contact__item">
                        <a :href="$app.settings('link_whatsapp')" class="contact__link" target="_blank">
                          WhatsApp
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="col-6 d-none d-lg-block">
                  <img class="img-responsive contact__img" src="~assets/images/general/bg_contact.png" alt="" />
                </div>
              </div>
              <div class="row contacts__form mt-5">
                <div class="col-12">
                  <div class="contacts__text mb-2">
                    <p v-html="$md.render($app.settings('contacts_text_bottom'))" />
                  </div>
                  <div class="form-row form-body align-items-center">
                    <div class="col-lg-6 col-12">
                      <b-form-group :disabled="loading">
                        <b-form-input v-model="record.name" placeholder="Имя Фамилия" autofocus required />
                      </b-form-group>
                      <b-form-group :disabled="loading">
                        <input-phone v-model="record.phone" placeholder="Номер телефона" required max-length="13" />
                      </b-form-group>
                      <b-form-group :disabled="loading" class="mb-lg-0">
                        <b-form-input
                          v-model.trim="record.email"
                          type="email"
                          placeholder="Адрес электронной почты"
                          required
                        />
                      </b-form-group>
                    </div>
                    <div class="col-lg-6 col-12 align-self-stretch">
                      <b-form-textarea
                        v-model="record.message"
                        placeholder="Ваше сообщение"
                        required
                        no-resize
                        :disabled="loading"
                        rows="5"
                        class="h-100"
                      ></b-form-textarea>
                    </div>
                  </div>
                  <div class="form-row form-footer d-flex align-items-center justify-content-center mt-3">
                    <button class="button" :disabled="loading" @click.prevent="onSubmit">Отправить</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  auth: false,
  data() {
    return {
      loading: false,
      record: {
        name: '',
        phone: '',
        email: '',
        message: '',
      },
    }
  },
  created() {
    this.$app.header_image.set(false)
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$axios
        .post('web/feedback', this.record)
        .then(() => {
          for (const i in this.record) {
            if (Object.prototype.hasOwnProperty.call(this.record, i)) {
              this.record[i] = ''
            }
          }
          this.$notify.success({message: 'Ваше сообщение успешно отправлено!'})
        })
        .catch(() => {})
        .finally(() => {
          this.loading = false
        })
    },
  },
  head() {
    return {
      title: 'Крафти / Контакты',
    }
  },
}
</script>
