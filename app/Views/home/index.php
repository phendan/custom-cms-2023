<h1>Home</h1>

<?php if ($data['user']->isLoggedIn()): ?>
    Hey there, <?php echo $data['user']->getUsername(); ?>!
    <a href="/logout">Sign Out</a>
<?php else: ?>
    <a href="/login">Sign In</a>
<?php endif; ?>

<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda porro excepturi saepe earum doloribus, adipisci eos nostrum nesciunt cum in deleniti ipsum quas asperiores veritatis libero aperiam tempora nulla ipsam atque dolore voluptate magnam. Distinctio voluptate consequuntur, nesciunt commodi a incidunt. Minus ducimus quas corrupti minima dolorum accusantium voluptatibus facere, sit vel odit iure officia temporibus ex sed, aspernatur quae tempora at perspiciatis consequatur sequi, nihil vitae. Laborum voluptatum tempore numquam perspiciatis sit, animi repellendus veniam eius laboriosam! Rem molestiae consectetur consequuntur ratione ducimus non iure repellendus id quam possimus repudiandae reiciendis, dolor quisquam quos commodi quaerat. Blanditiis eum maxime dolorem numquam assumenda deleniti iure fugit consequuntur, eligendi similique eius enim reiciendis in vero quas nobis impedit consequatur quae veritatis, harum minima dolore! Illo beatae, eligendi, eaque asperiores nihil ipsum est praesentium rem perferendis consectetur quaerat vel numquam expedita consequatur veniam possimus, incidunt maiores aliquid repellendus at! Maxime, veritatis! Aliquid officia eaque quas delectus asperiores! Repellat commodi laboriosam doloribus accusantium facere eaque nihil iusto beatae possimus rerum fugiat voluptatem blanditiis in ipsam, dolor cumque unde tenetur officia vitae harum! Tempora velit qui sit? Voluptatibus vero laborum recusandae eos blanditiis repellat, provident voluptas nostrum dolorem earum deleniti? Modi voluptas quae commodi?</p>
