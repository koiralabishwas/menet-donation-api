import Layout from "@/layout";

import DonateDialog from "@/components/donate_dialog";

type Props = {
    project: string;
};

export default function Project({ project }: Props) {
    return (
        <Layout className="max-w-screen-lg mx-auto bg-white md:pt-5 md:px-10">
            <img
                src="/images/altervoice.jpg"
                alt="Altervoice"
                className="w-full md:rounded-xl md:max-h-90"
            />
            <article className="p-2 pt-5 pb-40 leading-10 prose max-w-none prose-h1:text-primary prose-h1:font-bold prose-h1:m-0">
                <h1>{project}</h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam
                    ornare, nibh eu cursus tincidunt, massa libero sollicitudin
                    massa, sit amet euismod ipsum erat a dolor. Class aptent
                    taciti sociosqu ad litora torquent per conubia nostra, per
                    inceptos himenaeos. Aenean dignissim rhoncus eros id
                    pulvinar. Nam laoreet ante quis dui fringilla, id feugiat
                    neque egestas. Phasellus arcu dui, tempor et lectus ac,
                    tincidunt posuere magna. Duis congue finibus mi. In hac
                    habitasse platea dictumst. Nulla non blandit quam, eleifend
                    eleifend metus. Vivamus volutpat felis eu mi ornare
                    volutpat. Duis porttitor, lectus eget placerat volutpat,
                    odio erat accumsan nibh, a tempor tortor orci a nisl. Sed
                    sagittis magna sed massa tristique, quis varius dolor
                    tempus. Aliquam ultrices arcu nec augue facilisis consequat.
                    Vestibulum ultricies magna felis, laoreet posuere justo
                    pulvinar vitae. Orci varius natoque penatibus et magnis dis
                    parturient montes, nascetur ridiculus mus. Pellentesque at
                    tellus ac ante tempus semper. Vestibulum vel enim sed augue
                    maximus luctus a quis metus. Aenean quis accumsan enim, ut
                    faucibus ante. Proin ut diam ex. Curabitur ullamcorper,
                    velit in mollis maximus, mauris arcu cursus ligula, sit amet
                    ultrices quam mi eget odio. Suspendisse maximus, odio a
                    laoreet tempus, est tortor tempus eros, in tincidunt tellus
                    ante non dolor. Sed facilisis aliquet risus, vitae egestas
                    tellus auctor non. Praesent pretium leo ac eros consectetur
                    vehicula. Nam a mollis tellus. Integer ultrices, sem quis
                    ultrices accumsan, ex nulla tincidunt lacus, in tincidunt
                    ante arcu eu urna. Maecenas ullamcorper laoreet tincidunt.
                    Cras interdum enim ut diam dapibus fermentum. Nullam et
                    malesuada eros. Morbi id semper ante. Cras et magna dui.
                    Interdum et malesuada fames ac ante ipsum primis in
                    faucibus. Ut placerat euismod magna eget lobortis. Aenean id
                    arcu nec sem fringilla lacinia rutrum vel arcu. Fusce ac
                    hendrerit eros. Nullam quis justo fringilla, pharetra est
                    feugiat, fringilla massa. Morbi vitae dolor turpis.
                    Suspendisse molestie accumsan augue eget euismod. Etiam
                    convallis molestie efficitur. Donec congue lacinia
                    ullamcorper. Quisque id nisl sed turpis volutpat tempor sed
                    et justo. Morbi nunc arcu, ultricies a augue maximus,
                    consectetur efficitur est. Cras posuere risus sit amet felis
                    viverra, ac tristique magna facilisis. Integer pellentesque
                    neque id turpis suscipit efficitur. Curabitur aliquam lorem
                    ac felis porta pulvinar. Sed vehicula, erat nec mattis
                    aliquet, libero lacus efficitur nisl, ut sagittis turpis
                    lorem sed purus. In id leo efficitur mi hendrerit hendrerit
                    consectetur et est. Pellentesque tincidunt, mauris sed
                    venenatis posuere, ante est commodo metus, in fermentum enim
                    lorem eu risus. Nullam lobortis elementum magna. Ut ut
                    pulvinar magna. Vestibulum volutpat et erat id mollis. Morbi
                    iaculis egestas magna vel ornare. Quisque vehicula justo ut
                    malesuada vehicula. Etiam rhoncus sit amet libero
                    sollicitudin pharetra. In a tempus purus. Nam commodo
                    tincidunt ligula, vel dapibus sem consequat nec. Suspendisse
                    consectetur luctus eros. Mauris gravida vitae erat at
                    consectetur. Sed pellentesque sapien auctor arcu dictum, et
                    venenatis urna rhoncus. Proin rutrum neque enim, quis
                    elementum urna ornare non. Mauris rutrum semper ligula sit
                    amet sollicitudin. Integer vitae nibh rhoncus, laoreet lacus
                    ac, hendrerit erat. Sed nec fermentum risus. Suspendisse id
                    ultricies nisi, vel pulvinar tortor. Nunc eu pharetra nunc,
                    eget consectetur libero. Vivamus pellentesque eu lectus
                    vitae pharetra. Mauris laoreet nisi at ornare blandit.
                    Integer congue sit amet nibh ut sodales. Phasellus tincidunt
                    gravida purus, id tempor dui feugiat condimentum. Aliquam
                    sit amet augue convallis, lobortis augue quis, sollicitudin
                    lectus. In scelerisque nisi sit amet nibh placerat, a
                    sollicitudin erat aliquet. Nullam mattis tortor arcu, eu
                    suscipit quam aliquet sodales. Nam quis mauris id turpis
                    aliquet accumsan non ut tellus. Donec venenatis, justo et
                    aliquet facilisis, dolor turpis pharetra est, eget accumsan
                    velit est vel lorem. Pellentesque venenatis eget eros et
                    cursus. Pellentesque dapibus fringilla neque sit amet
                    gravida. Quisque eu aliquet odio. Etiam porttitor rhoncus
                    tellus, at lacinia ante. Nunc volutpat at est in commodo.
                    Maecenas aliquet, leo vitae ullamcorper tristique, odio
                    mauris sodales mi, a ullamcorper nibh purus ac diam. Donec
                    varius ac lectus nec maximus. Pellentesque rutrum, leo sed
                    ullamcorper pharetra, lacus purus ullamcorper ex, vel semper
                    urna magna vel lorem. Fusce velit nibh, maximus non
                    pellentesque vel, consequat eu ex. Nam urna nunc, elementum
                    id mauris nec, fringilla elementum lacus. Integer non justo
                    quam. Nulla ut molestie neque. Cras ullamcorper scelerisque
                    elit, sed lacinia dui varius ac. Quisque euismod vel lectus
                    sed vulputate. Phasellus nec arcu elementum, condimentum
                    ligula non, tincidunt eros. Maecenas rhoncus tempus augue a
                    suscipit. Donec non lectus nec dui posuere imperdiet. Proin
                    tincidunt facilisis blandit. Phasellus non felis
                    sollicitudin, porttitor elit at, luctus elit. Mauris
                    venenatis quam ante, suscipit fermentum diam facilisis at.
                    Phasellus pharetra, arcu eget eleifend dignissim, magna
                    lorem egestas sapien, eu aliquet eros orci ac nunc. Donec
                    pulvinar quam id lacus tempus, id facilisis nisi feugiat.
                    Vestibulum elementum elementum libero eget pulvinar. Nulla
                    vestibulum et libero in congue. Donec vehicula eros eget est
                    efficitur, non rutrum velit laoreet. Donec sed tempor lacus.
                    Ut venenatis ex vitae consectetur scelerisque. Curabitur
                    molestie quam nunc, ut dictum velit pellentesque sit amet.
                    Proin et eros cursus, tempor ipsum in, hendrerit urna.
                    Integer imperdiet velit felis, non aliquam ipsum rhoncus
                    non. Donec mollis mauris laoreet ligula scelerisque congue.
                    Donec quis interdum nibh, quis facilisis ipsum. Donec eget
                    pulvinar orci. Nulla vel neque eu arcu vehicula scelerisque.
                    Pellentesque eu quam arcu. In condimentum nibh viverra
                    accumsan laoreet. Ut vel consectetur mi. Donec quis tempus
                    augue. Orci varius natoque penatibus et magnis dis
                    parturient montes, nascetur ridiculus mus. Curabitur metus
                    magna, tristique vel molestie ac, efficitur id tortor.
                    Pellentesque habitant morbi tristique senectus et netus et
                    malesuada fames ac turpis egestas. Proin id quam massa.
                    Donec tristique volutpat lorem euismod gravida. Nullam at
                    urna et dui lacinia pulvinar. In ut quam sit amet metus
                    tempus sollicitudin vel id quam. Duis bibendum, augue a
                    pellentesque sagittis, sem dolor facilisis mauris, commodo
                    bibendum lorem odio eget neque. Quisque erat augue, laoreet
                    quis nulla ut, porttitor placerat ipsum. Suspendisse
                    potenti. Aenean venenatis quam ipsum, et euismod dolor
                    facilisis eu. Nam augue nisl, eleifend placerat tortor sed,
                    tincidunt pellentesque felis. Vivamus ullamcorper felis
                    elit, sed ultricies sapien facilisis.
                </p>
            </article>
            <DonateDialog />
        </Layout>
    );
}
