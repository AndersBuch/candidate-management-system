<script setup>
import Header from '@/components/Header.vue'
import HeroSectionPageTwo from '@/components/HeroSectionPageTwo.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/Button.vue'
</script>

<template>
  <Header />
  <HeroSectionPageTwo />
  <main>
    <div class="headerText">
    <BasicIconAndLogo name="LPSLogo" :small="true" />
    <h2>Lihme Protein Solutions ApS</h2>
    </div>

    <div class="mainSection">
        <h3>Oversigt</h3>
        <p>Brænder du for at arbejde med procesudstyr og 
            bidrage til udviklingen af fremtidens fødevaretekno-logi? 
            Har du erfaring med at styre og overvåge 
            produktionsprocesser – og lyst til at være en vigtig del 
            af vores udviklingsteam? Så er du måske den procesoperatør, 
            vi leder efter.</p>
            <h3> Jobbet:</h3>
            <ul>
                <li>
                    Udføre tests på vores pilotanlæg
                </li>
                <li>
                    Klargøre og forberede anlæg og råvarer til testkørsler
                </li>
                <li>
                    Betjene og overvåge anlægget for at sikre, at processerne forløber som planlagt
                </li>
                <li>
                    Indsamle og dokumentere data fra pilotanlægget samt medvirke til at analysere resultaterne
                </li>
                <li>
                    Bidrage til optimering af udviklede vore pilotanlæg og processer baseret på testdata
                </li>
                 <li>
                    Afprøve nye teknologier og metoder samt hjælpe me d at løse tekniske problemer, der opstår under test
                </li>
                <li>
                    Deltage i kundebesøg (ofte i udlandet) for at gennemføre pilotkørsler og tests på stedet. Du kan forvente 10-15 rejsedage årligt fordelt på 2-4 rejser.
                </li>
            </ul>
            <h3>Vi tilbyder:</h3>
            <p>Hos os bliver du en del af en internationalt orienteret 
                virksomhed i vækst med høj faglighed, stor frihed 
                og en uformel kultur.</p>
                <p class="boldTekst">Vi lægger vægt på:</p>
                <ul>
                    <li> Frihed under ansvar – vi stoler på, at du gør dit bedste</li>
                    <li>Fokus og enkelhed – vi skærer ind til benet og skaber værdi for kunden</li>
                    <li>Respekt – for hinanden og for hinandens arbejde</li>
                </ul>

                <p>Du får en selvstændig og varieret rolle med 
                    mulighed for efteruddannelse og faglig 
                    opkvalificering efter behov.</p>
    </div>
    <RouterLink to="/pagethree">
    <Button label="Søg Job" type="default" aria-label="Ansøg nu" />
    </RouterLink>
  </main>
</template>


<style scoped lang="scss">

.headerText {
    display: flex;
    align-items: center;
    gap: 16px;
    margin: 40px auto 0 auto;


    h2 {
        @include heading2;
    }
}

.mainSection {
    margin: 40px auto;
    padding: 0 16px;

    h3 {
        @include heading3;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    p {
        @include bodyText;
        margin-bottom: 16px;
    }

    .boldTekst {
        @include boldBodyText
    }

ul {
  list-style: none; 
  margin-left: 20px;
  margin-bottom: 20px;

  li {
    @include bodyText;
    margin-bottom: 10px;
    position: relative; 
    padding-left: 28px;

    &::before {
      content: '✔';
      position: absolute;
      left: 0;
      top: 0;
      color: $goodGreen; 
      font-size: 20px;
      font-weight: 700;
      line-height: 1; 
    }
  }
}
}
    button {
    margin-bottom: 40px;
    }
</style>
