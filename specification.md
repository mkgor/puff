# Puff engine specification

#### Grammatic rules
- **[** , **]**  - expression token.
- **{ variable }** - print variable

#### Methods

- **import** - imports content from another template file (ex. **[import "base"]** will import **"\<TEMPLATE_DIRECTORY>/base.puff"** file)
- **for** - `foreach` cycle
- **use** - telling to compiler that we can use some extension in template (ex. **[use "CustomDates"]**)
- **if** - if-else

#### Features

- **Data filters** - ex. `[% createdAt ~ date('Y-m-d') %]`
- **Data abstraction layer** - it is not important you are using object or array in template - you can access to any data same way. ex. `[% post.createdAt %]` can be `$post['createdAt']` or `$post->createdAt` or `$post->createdAt()`;
- **Importable extensions** - extensions are not loaded, until we don't say to compiler to include it into template file

**Template example**

````html
@head
[use "CustomExtension"]  
[use "NewDataFiltersExtension"]  
@endhead

[import "base"]
<main>
  <div class="wrapper">
    <h1>Hello, { user.getUsername ~ upperCase }</h1>
    <time>{ currentTime ~ toISO }</time>

    <div class="messages">
      [for messages as message]
        <div class="message">
          { message.content }
        </div>
      [endfor]
    </div>
  </div>
</main>
````
