<?php

/**
 *
 * @link              https://criacaocriativa.com
 * @since             1.0.0
 * @package           WP_Box_Lottery 
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$name       = (isset($results['loteria'])) ? esc_attr($results['loteria']) : '';
$title      = (isset($results['nome'])) ? esc_attr($results['nome']) : 'N/A';
$lottery    = (isset($results['concurso'])) ? esc_attr($results['concurso']) : 0;
$date       = (isset($results['data'])) ? esc_attr($results['data']) : 'N/A';
$local      = (isset($results['local'])) ? esc_attr($results['local']) : 'N/A';

$dozens     = (isset($results['dezenas'])) ? $results['dezenas'] : 'N/A';
$awards     = (isset($results['premiacoes'])) ? $results['premiacoes'] : 'N/A';

$premium    = (isset($awards)) ? $this->get_total_premium($awards) : 'R$ 0';

$accumulated        = (isset($results['acumulou'])) ? $results['acumulou'] : 0;
$next_accumulated   = (isset($results['acumuladaProxConcurso'])) ? esc_attr($results['acumuladaProxConcurso']) : 'R$ 0';
$next_date_lottery  = (isset($results['dataProxConcurso'])) ? esc_attr($results['dataProxConcurso']) : '';
$next_lottery       = (isset($results['proxConcurso'])) ? esc_attr($results['proxConcurso']) : '';
$team_heart         = (isset($results['timeCoracao'])) ? $results['timeCoracao'] : 0;
$month_luck         = (isset($results['mesSorte'])) ? $results['mesSorte'] : 0;
?>

<link rel="stylesheet" href="<?php echo $plugin_dir_url; ?>assets/css/style.css" media="all">

<section class="lottery-totem lot-<?php echo $name; ?>">
   <header class="lottery-totem__header">
      <div class="lottery-totem__header-grid">
         <div class="lottery-totem__header-grid__result">
            <div class="result__title">
               <h2><?php echo $title; ?></h2>
            </div>
            <div class="result__draw-date">
                Sorteio: <strong><?php echo $date; ?></strong>
            </div>
            <div class="result__draw">
                Concurso: <strong><?php echo $lottery; ?></strong>
            </div>
            <div class="result__local">
               <div class="text-center text-sm-left">
                <span>Local de Sorteio:</span><br>
                <strong><?php echo $local; ?></strong>
            </div>
            </div>
            <div class="result__prize">
                <div class="text-left">Valor do prêmio</div>
                <div class="result__prize__wrap">
                    <span>R$</span> 
                    <span class="result__prize__value"><?php echo str_replace('R$ ', '', $premium); ?></span>
                </div>
            </div>
         </div>
         <div></div>
      </div>
   </header>
   <div class="lottery-totem__modules-grid">
      <div class="lottery-totem__body">
         <div class="lottery-totem__body__content card">
            <div class="result__content__wrap result__content__wrap--tens">
               <div class="result__tens-grid">
                <?php if (isset($dozens)) { ?>
                    <?php foreach ($dozens as $key => $row) { ?>
                  <div class="lot-bg-light"><span><?php echo $row; ?></span></div>
                    <?php } ?>
                <?php } ?>
               </div>
                <?php if ($accumulated) { ?>
                <p class="text-center text-uppercase color-primary">                    
                    <strong>Acumulou!</strong>
                </p>
                <?php } ?>
            </div>
            <table class="result__table-prize">
               <tbody>
                  <tr>
                    <td class="text-center">
                        <strong>Premiação</strong>
                    </td>
                    <td class="text-center">
                        <strong>Ganhadores</strong>
                    </td>
                    <td class="text-center">
                        <strong>Prêmio</strong>
                    </td>
                  </tr>
                    <?php if (isset($awards)) { ?>
                        <?php foreach ($awards as $key => $row) { ?>
                        <tr>
                            <td class="text-center"><?php echo $row['acertos']; ?></td>
                            <td class="text-center"><?php echo $row['vencedores']; ?></td>
                            <td class="text-center">
                                R$ <?php echo $row['premio']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
               </tbody>
            </table>
         </div>
      </div>
      <div class="lottery-totem__aside">
         <div class="lottery-totem__aside__wrap">
            <div class="lottery-totem__nextdraw">
               <div class="lottery-totem__nextdraw__title">Próximo Concurso</div>
               <div class="card mt-0">
                  <div class="lottery-totem__nextdraw__block card">
                    <div class="lottery-totem__nextdraw__info">
                        <div class="lottery-totem__nextdraw__prize">
                            <div class="lottery-totem__nextdraw__prize__wrap">
                                <span>R$</span> 
                                <span class="lottery-totem__nextdraw__prize__value">
                                    <?php echo str_replace('R$ ', '', $next_accumulated); ?>
                                </span>
                            </div>
                        </div>
                        <?php if ($accumulated) { ?>
                        <div class="lottery-totem__nextdraw__is-jackpot">
                            <span>Acumulada!</span>
                        </div>
                        <?php } ?>
                        <div class="lottery-totem__nextdraw__draw-date">
                            Sorteio: <strong><?php echo $next_date_lottery; ?></strong>
                        </div>
                        <div class="lottery-totem__nextdraw__draw d-none d-lg-block">
                            Concurso: <strong><?php echo $next_lottery; ?></strong>
                        </div>
                    </div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>
</section>