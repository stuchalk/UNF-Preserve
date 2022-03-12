<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:spr="http://chalk.coas.unf.edu/springer/"
	xmlns:foxml="info:fedora/fedora-system:def/foxml#"
	xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:kml="http://www.opengis.net/kml/2.2"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:rel="info:fedora/fedora-system:def/relations-external#"
	xmlns:mod="info:fedora/fedora-system:def/model#"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" exclude-result-prefixes="xs spr" version="2.0">
	<xsl:output method="xml" omit-xml-declaration="yes" indent="no"/>
	<xsl:strip-space elements="*"/>
	<xsl:variable name="props" select="//foxml:property"/>
	<xsl:variable name="dcstreams" select="//foxml:datastreamVersion[starts-with(@ID,'DC.')]"/>
	<xsl:variable name="exifstreams" select="//foxml:datastreamVersion[starts-with(@ID,'EXIF.')]"/>
	<xsl:variable name="kmlstreams" select="//foxml:datastreamVersion[starts-with(@ID,'KML.')]"/>
	<xsl:variable name="relstreams" select="//foxml:datastreamVersion[starts-with(@ID,'RELS-EXT.')]"/>
	<xsl:variable name="srcstreams" select="//foxml:datastreamVersion[starts-with(@ID,'Source.')]"/>
	<xsl:variable name="tmbstreams" select="//foxml:datastreamVersion[starts-with(@ID,'THUMB.')]"/>
	<xsl:variable name="quot">"</xsl:variable>
	<xsl:variable name="bslash">\\"</xsl:variable>
	<xsl:template match="/">
	{
	"pid": "<xsl:value-of select="*/@PID"/>",
		"props": {
			<xsl:for-each select="$props">
				"<xsl:value-of select="@NAME"/>": "<xsl:value-of select="@VALUE"/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
		},
		"dcs": [
			<xsl:for-each select="$dcstreams">
				{
				"attrs": {
					<xsl:for-each select="@*">
						"<xsl:value-of select="lower-case(name())"/>": "<xsl:value-of select="."/>"
						<xsl:if test="position() != last()">
							<xsl:text>,</xsl:text>
						</xsl:if>
					</xsl:for-each>
					},
				"content": {
				<xsl:for-each select="*//dc:*">
					"<xsl:value-of select="lower-case(local-name())"/>": "<xsl:value-of select="."/>"
					<xsl:if test="position() != last()">
						<xsl:text>,</xsl:text>
					</xsl:if>
				</xsl:for-each>
					}
				}
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
		],
		"exifs": [
		<xsl:for-each select="$exifstreams">
			{
			"attrs": {
			<xsl:for-each select="@*">
				"<xsl:value-of select="lower-case(name())"/>": "<xsl:value-of select="."/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
			},
			"content": {
			<xsl:for-each select="*//exif/child::node()">
				"<xsl:value-of select="lower-case(local-name())"/>": "<xsl:value-of select="."/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
				}
			}
			<xsl:if test="position() != last()">
				<xsl:text>,</xsl:text>
			</xsl:if>
		</xsl:for-each>
		],
		"kmls": [
		<xsl:for-each select="$kmlstreams">
			{
			"attrs": {
			<xsl:for-each select="@*">
				"<xsl:value-of select="lower-case(name())"/>": "<xsl:value-of select="."/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
			},
			"content": {
			<xsl:variable name="kml" select="*//kml:kml"/>
			"name": "<xsl:value-of select="$kml/kml:Placemark/kml:name"/>",
			"desc": "<xsl:value-of select="$kml/kml:Placemark/kml:description"/>",
			"coordinates": "<xsl:value-of select="$kml/kml:Placemark/kml:Point/kml:coordinates"/>"
				}
			}
			<xsl:if test="position() != last()">
				<xsl:text>,</xsl:text>
			</xsl:if>
		</xsl:for-each>
		],
		"rels": [
		<xsl:for-each select="$relstreams">
			{
			"attrs": {
			<xsl:for-each select="@*">
				"<xsl:value-of select="lower-case(name())"/>": "<xsl:value-of select="."/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
				},
			"content": {
			<xsl:for-each select="*//rdf:Description/child::node()">
				<xsl:choose>
					<xsl:when test="@rdf:resource">
						"<xsl:value-of select="lower-case(local-name())"/>": "<xsl:value-of select="@rdf:resource"/>"
					</xsl:when>
					<xsl:otherwise>
						"<xsl:value-of select="lower-case(local-name())"/>": "<xsl:value-of select="."/>"
					</xsl:otherwise>
				</xsl:choose>
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
				}
			}
			<xsl:if test="position() != last()">
				<xsl:text>,</xsl:text>
			</xsl:if>
		</xsl:for-each>
		],
		"sources": [
		<xsl:for-each select="$srcstreams">
			{
			"attrs": {
			<xsl:for-each select="@*">
				"<xsl:value-of select="lower-case(name())"/>": "<xsl:value-of select="."/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
				},
			"content": {
			"md5": "<xsl:value-of select="foxml:contentDigest/@DIGEST"/>",
			"url": "<xsl:value-of select="foxml:contentLocation/@REF"/>"
				}
			}
			<xsl:if test="position() != last()">
				<xsl:text>,</xsl:text>
			</xsl:if>
		</xsl:for-each>
		],
		"thumbs": [
		<xsl:for-each select="$tmbstreams">
			{
			"attrs": {
			<xsl:for-each select="@*">
				"<xsl:value-of select="lower-case(name())"/>": "<xsl:value-of select="."/>"
				<xsl:if test="position() != last()">
					<xsl:text>,</xsl:text>
				</xsl:if>
			</xsl:for-each>
			},
			"content": {
			"url": "<xsl:value-of select="foxml:contentLocation/@REF"/>"
			}
			}
			<xsl:if test="position() != last()">
				<xsl:text>,</xsl:text>
			</xsl:if>
		</xsl:for-each>
		]
	}
	</xsl:template>
</xsl:stylesheet>
